<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /** Public pages only. */
    public const PAGES = ['home', 'about', 'faq', 'help', 'contact', 'privacy', 'terms', 'cookies', 'privacy-request', 'accessibility'];

    public function sitemap(): Response
    {
        $updated = config('sinowala.legal_updated');
        $urls = collect(self::PAGES)->map(fn ($name) => '  <url><loc>'.e(route($name)).'</loc><lastmod>'.$updated.'</lastmod></url>')->implode("\n");

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n".$urls."\n</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $body = implode("\n", [
            'User-agent: *',
            'Disallow: /app',
            'Disallow: /admin',
            'Disallow: /u/',
            'Disallow: /join/',
            'Allow: /',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
