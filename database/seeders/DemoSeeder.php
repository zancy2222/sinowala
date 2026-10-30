<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\Company;
use App\Models\Membership;
use App\Models\User;
use App\Support\Today;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use RuntimeException;

/**
 * Optional sample data (local only): one company with 4 members and a week of absences,
 * and one personal user with entries and private notes.
 *
 *   php artisan db:seed --class=DemoSeeder
 *
 * Every demo account uses the password: DemoPass-2026
 */
class DemoSeeder extends Seeder
{
    public const PASSWORD = 'DemoPass-2026';

    public function run(): void
    {
        if (app()->isProduction()) {
            throw new RuntimeException('DemoSeeder is for local machines only.');
        }

        $monday = Today::date()->startOfWeek(Carbon::MONDAY);

        $company = Company::createWithCodes('Kapihan sa Kanto');
        $company->status = Company::ACTIVE;
        $company->approved_at = now();
        $company->save();

        $people = [
            ['Maria Villanueva', 'maria@demo.sinowala.test', Membership::ROLE_ADMIN, []],
            ['Ana Santos', 'ana@demo.sinowala.test', Membership::ROLE_MEMBER, [4 => 'full']],
            ['Ben Cruz', 'ben@demo.sinowala.test', Membership::ROLE_MEMBER, [1 => 'am', 3 => 'pm']],
            ['Dina Lopez', 'dina@demo.sinowala.test', Membership::ROLE_MEMBER, [2 => 'full', 3 => 'full']],
        ];

        foreach ($people as [$name, $email, $role, $days]) {
            $user = $this->user($name, $email);
            Membership::create(['user_id' => $user->id, 'company_id' => $company->id, 'role' => $role, 'joined_at' => now()->subWeeks(3)]);
            foreach ($days as $offset => $part) {
                $this->absence($user, $monday->addDays($offset)->toDateString(), $part);
            }
        }

        // A personal user (no company), with private notes.
        $solo = $this->user('Paolo Garcia', 'paolo@demo.sinowala.test');
        $this->absence($solo, Today::date()->addDays(2)->toDateString(), 'am', 'Renew ng passport sa DFA Aseana');
        $this->absence($solo, Today::date()->addDays(9)->toDateString(), 'full', 'Kasal ng pinsan sa Batangas');
        $this->absence($solo, Today::date()->addDays(10)->toDateString(), 'full', 'Kasal ng pinsan sa Batangas');
        $this->absence($solo, Today::date()->addDays(17)->toDateString(), 'pm', 'Dentist');

        $this->command?->info('Demo data ready. Password for every demo account: '.self::PASSWORD);
        $this->command?->info('Company code: '.$company->code);
    }

    private function user(string $name, string $email): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => self::PASSWORD,
            'terms_version' => config('sinowala.terms_version'),
            'privacy_version' => config('sinowala.privacy_version'),
            'accepted_at' => now(),
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function absence(User $user, string $date, string $part, ?string $note = null): void
    {
        $absence = new Absence(['date' => $date, 'part' => $part]);
        $absence->user_id = $user->id;
        $absence->save();

        if ($note) {
            $n = $absence->note()->make(['note' => $note]);
            $n->user_id = $user->id;
            $n->save();
        }
    }
}
