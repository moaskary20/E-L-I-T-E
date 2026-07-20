<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoSettingsController extends Controller
{
    public function index(): View
    {
        $settings = SiteSetting::seoDefaults();
        $sitemapUrl = $settings['site_url'].'/sitemap.xml';

        return view('admin.seo.index', compact('settings', 'sitemapUrl'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'google_site_verification' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'site_url' => 'nullable|url|max:255',
            'robots_index' => 'nullable|boolean',
        ]);

        $siteUrl = isset($validated['site_url'])
            ? rtrim($validated['site_url'], '/')
            : null;

        SiteSetting::setMany([
            'google_site_verification' => trim((string) ($validated['google_site_verification'] ?? '')),
            'seo_title' => trim((string) ($validated['seo_title'] ?? '')),
            'seo_description' => trim((string) ($validated['seo_description'] ?? '')),
            'site_url' => $siteUrl ?? '',
            'robots_index' => $request->boolean('robots_index') ? '1' : '0',
        ]);

        return back()->with('success', 'SEO settings saved successfully.');
    }
}
