<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** /admin: Super Admin only, and only with two-factor authentication turned on. */
class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user && $user->is_super_admin && $user->hasVerifiedEmail(), 404);

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->to(route('settings').'#security')->with('toast', __('admin.need_2fa'));
        }

        return $next($request);
    }
}
