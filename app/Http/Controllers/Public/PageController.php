<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('public.home');
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function faq(): View
    {
        return view('public.faq');
    }

    public function help(): View
    {
        return view('public.help');
    }

    public function privacy(): View
    {
        return view('public.legal', ['doc' => 'privacy', 'version' => config('sinowala.privacy_version')]);
    }

    public function terms(): View
    {
        return view('public.legal', ['doc' => 'terms', 'version' => config('sinowala.terms_version')]);
    }

    public function cookies(): View
    {
        return view('public.legal', ['doc' => 'cookies', 'version' => config('sinowala.cookie_version')]);
    }

    public function accessibility(): View
    {
        return view('public.legal', ['doc' => 'accessibility', 'version' => '1.0']);
    }
}
