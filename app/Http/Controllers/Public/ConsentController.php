<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsentRequest;
use App\Models\ConsentLog;
use Illuminate\Http\Response;

/** Logs each cookie choice (timestamp, version, choices). The cookie itself is set by the banner. */
class ConsentController extends Controller
{
    public function store(ConsentRequest $request): Response
    {
        $data = $request->validated();

        ConsentLog::create([
            'user_id' => $request->user()?->id,
            'consent_id' => $data['consent_id'],
            'version' => config('sinowala.cookie_version'),
            'choices' => ['necessary' => true, 'analytics' => (bool) $data['analytics']],
            'ip' => $request->ip(),
        ]);

        return response()->noContent();
    }
}
