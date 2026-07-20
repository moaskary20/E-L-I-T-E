@extends('layouts.admin')

@section('title', 'SEO / Search Console | Elite Physio Clinics')

@section('content')
<div class="seo-page">
    <h2 class="admin-page-title">SEO / Search Console</h2>
    <p class="seo-desc">Connect your site to Google Search Console and manage basic SEO meta tags shown on public pages.</p>

    @if(session('success'))
        <div class="admin-success" style="margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="admin-error" style="margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.seo.update') }}" class="seo-form">
        @csrf
        @method('PUT')

        <div class="seo-card">
            <div class="seo-card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                <div>
                    <div class="seo-card-title">Google Search Console</div>
                    <div class="seo-card-hint">Paste only the <code>content</code> value from the HTML meta tag verification method.</div>
                </div>
            </div>
            <div class="seo-card-body">
                <div class="seo-field">
                    <label for="google_site_verification">Google site verification code</label>
                    <input
                        type="text"
                        id="google_site_verification"
                        name="google_site_verification"
                        value="{{ old('google_site_verification', $settings['google_site_verification']) }}"
                        placeholder="e.g. AbCdEf123..."
                        autocomplete="off"
                    />
                    <p class="seo-field-help">Search Console → Add property → HTML tag → copy the code inside <code>content="..."</code>.</p>
                </div>
            </div>
        </div>

        <div class="seo-card">
            <div class="seo-card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5A2.5 2.5 0 0 1 4 19.5z"/><path d="M8 7h8"/><path d="M8 11h8"/><path d="M8 15h4"/></svg>
                <div>
                    <div class="seo-card-title">Page SEO</div>
                    <div class="seo-card-hint">Default title and description used on public pages.</div>
                </div>
            </div>
            <div class="seo-card-body">
                <div class="seo-field">
                    <label for="seo_title">SEO title</label>
                    <input
                        type="text"
                        id="seo_title"
                        name="seo_title"
                        value="{{ old('seo_title', $settings['seo_title']) }}"
                        maxlength="255"
                    />
                </div>
                <div class="seo-field">
                    <label for="seo_description">Meta description</label>
                    <textarea
                        id="seo_description"
                        name="seo_description"
                        rows="3"
                        maxlength="500"
                    >{{ old('seo_description', $settings['seo_description']) }}</textarea>
                    <p class="seo-field-help">Aim for about 150–160 characters for best display in Google results.</p>
                </div>
                <div class="seo-field seo-field-check">
                    <label class="seo-check">
                        <input type="checkbox" name="robots_index" value="1" @checked(old('robots_index', $settings['robots_index']) == '1') />
                        <span>Allow search engines to index this site</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="seo-card">
            <div class="seo-card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                <div>
                    <div class="seo-card-title">Site URL &amp; Sitemap</div>
                    <div class="seo-card-hint">Used for canonical links and the sitemap you submit in Search Console.</div>
                </div>
            </div>
            <div class="seo-card-body">
                <div class="seo-field">
                    <label for="site_url">Site URL</label>
                    <input
                        type="url"
                        id="site_url"
                        name="site_url"
                        value="{{ old('site_url', $settings['site_url']) }}"
                        placeholder="https://www.elitephysioclinics.co.uk"
                    />
                </div>
                <div class="seo-sitemap">
                    <div class="seo-sitemap-label">Sitemap URL (submit this in Search Console)</div>
                    <div class="seo-sitemap-row">
                        <code id="seo-sitemap-url">{{ $sitemapUrl }}</code>
                        <button type="button" class="admin-btn-ghost-v2" onclick="navigator.clipboard.writeText(document.getElementById('seo-sitemap-url').textContent); this.textContent='Copied'; setTimeout(() => this.textContent='Copy', 1500)">Copy</button>
                    </div>
                    <p class="seo-field-help">After saving, open Search Console → Sitemaps → paste this URL.</p>
                </div>
            </div>
        </div>

        <div class="seo-actions">
            <button type="submit" class="admin-btn-gold">Save SEO settings</button>
        </div>
    </form>
</div>
@endsection
