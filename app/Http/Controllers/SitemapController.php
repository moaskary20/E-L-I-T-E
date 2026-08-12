<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $base = rtrim(SiteSetting::seoDefaults()['site_url'], '/');
        $now = now()->toAtomString();

        $urls = [
            ['loc' => $base.'/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => $base.'/blog', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => $base.'/privacy-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        foreach (array_keys(config('blog.posts', [])) as $slug) {
            $urls[] = [
                'loc' => $base.'/blog/'.$slug,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($url['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8')."</loc>\n";
            $xml .= "    <lastmod>{$now}</lastmod>\n";
            $xml .= '    <changefreq>'.$url['changefreq']."</changefreq>\n";
            $xml .= '    <priority>'.$url['priority']."</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
