<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** When the Terms or Privacy Policy version changes, ask again before the app opens. */
class EnsurePoliciesAccepted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->needsPolicyAcceptance() && ! $request->routeIs('policies.*', 'logout')) {
            return redirect()->route('policies.show');
        }

        return $next($request);
    }
}
