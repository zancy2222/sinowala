<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\PasswordCheckRequest;
use App\Http\Requests\ToggleRequest;
use App\Models\Absence;
use App\Models\Membership;
use App\Models\User;
use App\Support\Fmt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class SettingsController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $this->authorize('update', $user);

        return view('app.settings', [
            'user' => $user,
            'company' => $user->company(),
            'sessions' => $this->sessions($request),
        ]);
    }

    /** Browser sessions from the database session store. */
    private function sessions(Request $request): array
    {
        if (config('session.driver') !== 'database') {
            return [];
        }

        return DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->id)
            ->orderByDesc('last_activity')
            ->get(['id', 'ip_address', 'user_agent', 'last_activity'])
            ->map(fn ($s) => (object) [
                'device' => $this->describeAgent((string) $s->user_agent),
                'mobile' => (bool) preg_match('/Mobile|Android|iPhone|iPad/i', (string) $s->user_agent),
                'ip' => $s->ip_address,
                'current' => $s->id === $request->session()->getId(),
                'last_active' => Fmt::dateTime(Carbon::createFromTimestamp($s->last_activity)),
            ])->all();
    }

    private function describeAgent(string $agent): string
    {
        $browser = match (true) {
            str_contains($agent, 'Edg/') => 'Edge',
            str_contains($agent, 'OPR/') => 'Opera',
            str_contains($agent, 'Firefox/') => 'Firefox',
            str_contains($agent, 'Chrome/') => 'Chrome',
            str_contains($agent, 'Safari/') => 'Safari',
            default => __('app.settings.sessions.unknown_browser'),
        };
        $os = match (true) {
            str_contains($agent, 'Windows') => 'Windows',
            str_contains($agent, 'iPhone') || str_contains($agent, 'iPad') => 'iOS',
            str_contains($agent, 'Mac OS') => 'macOS',
            str_contains($agent, 'Android') => 'Android',
            str_contains($agent, 'Linux') => 'Linux',
            default => __('app.settings.sessions.unknown_os'),
        };

        return $browser.' · '.$os;
    }

    public function logoutOtherSessions(PasswordCheckRequest $request): RedirectResponse
    {
        Auth::logoutOtherDevices($request->validated('password'));

        if (config('session.driver') === 'database') {
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        return redirect()->to(route('settings').'#sessions')->with('toast', __('app.settings.sessions.done'));
    }

    public function toggleShare(ToggleRequest $request): RedirectResponse
    {
        $user = $request->user();
        $this->authorize('update', $user);

        $enabled = $request->boolean('enabled');
        if ($enabled && ! $user->share_token) {
            $user->regenerateShareToken();
        }
        $user->forceFill(['share_enabled' => $enabled])->save();

        return redirect()->to(route('settings').'#share')
            ->with('toast', $enabled ? __('app.settings.share.on') : __('app.settings.share.off'));
    }

    public function regenerateShare(Request $request): RedirectResponse
    {
        $user = $request->user();
        $this->authorize('update', $user);
        $user->regenerateShareToken();

        return redirect()->to(route('settings').'#share')->with('toast', __('app.settings.share.regenerated'));
    }

    public function shortcuts(ToggleRequest $request): RedirectResponse
    {
        $request->user()->forceFill(['shortcuts_enabled' => $request->boolean('enabled')])->save();

        return redirect()->to(route('settings').'#shortcuts')->with('toast', __('app.saved'));
    }

    /** "I-download ang data ko": profile, absences and own notes as JSON + CSV in one zip. */
    public function export(Request $request): BinaryFileResponse
    {
        $user = $request->user();
        $this->authorize('update', $user);

        $absences = Absence::where('user_id', $user->id)->with('note')->orderBy('date')->get();
        $membership = $user->membership;

        $data = [
            'exported_at' => now()->toIso8601String(),
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at?->toIso8601String(),
                'created_at' => $user->created_at?->toIso8601String(),
                'last_login_at' => $user->last_login_at?->toIso8601String(),
                'two_factor_enabled' => $user->hasTwoFactorEnabled(),
                'share_link_enabled' => $user->share_enabled,
                'shortcuts_enabled' => $user->shortcuts_enabled,
                'terms_version' => $user->terms_version,
                'privacy_version' => $user->privacy_version,
                'accepted_at' => $user->accepted_at?->toIso8601String(),
            ],
            'company' => $membership ? [
                'name' => $membership->company->name,
                'role' => $membership->role,
                'joined_at' => $membership->joined_at?->toIso8601String(),
            ] : null,
            'absences' => $absences->map(fn (Absence $a) => [
                'date' => $a->date->toDateString(),
                'part' => $a->part,
                'note' => $a->note?->note,
            ])->all(),
        ];

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['date', 'part', 'note'], escape: '');
        foreach ($data['absences'] as $row) {
            fputcsv($csv, [$row['date'], $row['part'], $row['note'] ?? ''], escape: '');
        }
        rewind($csv);

        $path = tempnam(sys_get_temp_dir(), 'sw-export-');
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::OVERWRITE);
        $zip->addFromString('sinowala-data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $zip->addFromString('sinowala-absences.csv', "\xEF\xBB\xBF".stream_get_contents($csv));
        $zip->close();
        fclose($csv);

        return response()->download($path, 'sinowala-data-'.now()->format('Y-m-d').'.zip', ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend();
    }

    /** "I-delete ang account ko": permanent. Absences, notes, share link, consent logs go with it. */
    public function destroyAccount(DeleteAccountRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->authorize('delete', $user);

        $membership = $user->membership;
        if ($membership && $membership->role === Membership::ROLE_ADMIN) {
            $company = $membership->company;
            $others = $company->memberships()->where('user_id', '!=', $user->id)->count();
            if ($others > 0 && $company->adminCount() === 1) {
                throw ValidationException::withMessages(['password' => __('app.settings.delete.last_admin')])
                    ->errorBag('deleteAccount');
            }
        }

        DB::transaction(function () use ($user, $membership) {
            if ($membership && $membership->company->memberships()->count() === 1) {
                $membership->company->delete();
            }
            if (config('session.driver') === 'database') {
                DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                    ->where('user_id', $user->id)->delete();
            }
            $user->delete(); // cascades: membership, absences, absence_notes, consent_logs
        });

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('toast', __('app.settings.delete.done'));
    }
}
