<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public const KEYS = [
        'google_site_verification',
        'seo_title',
        'seo_description',
        'site_url',
        'robots_index',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $all = self::allCached();

        if (array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '') {
            return $all[$key];
        }

        return $default;
    }

    public static function set(string $key, ?string $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('site_settings');
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            self::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('site_settings');
    }

    /**
     * @return array<string, string|null>
     */
    public static function allCached(): array
    {
        try {
            return Cache::remember('site_settings', 300, function () {
                return self::query()->pluck('value', 'key')->all();
            });
        } catch (\Throwable) {
            // Table may not exist yet before migrations run on a fresh deploy.
            return [];
        }
    }

    /**
     * Settings for the admin form / public head, with sensible defaults.
     *
     * @return array<string, string>
     */
    public static function seoDefaults(): array
    {
        $stored = self::allCached();
        $clinicName = (string) config('clinic.name', 'Elite Physio Clinics');

        $title = trim((string) ($stored['seo_title'] ?? ''));
        $description = trim((string) ($stored['seo_description'] ?? ''));
        $siteUrl = trim((string) ($stored['site_url'] ?? ''));
        $verification = trim((string) ($stored['google_site_verification'] ?? ''));

        return [
            'google_site_verification' => $verification,
            'seo_title' => $title !== ''
                ? $title
                : "{$clinicName} | Expert Physiotherapy Northampton",
            'seo_description' => $description !== ''
                ? $description
                : 'Personalised physiotherapy in Northampton. Expert treatment for musculoskeletal conditions, sports injuries, and paediatric physiotherapy with Wafaa Ibrahim.',
            'site_url' => rtrim($siteUrl !== '' ? $siteUrl : (string) config('app.url', 'https://www.elitephysioclinics.co.uk'), '/'),
            'robots_index' => array_key_exists('robots_index', $stored)
                ? (string) $stored['robots_index']
                : '1',
        ];
    }

    public static function robotsContent(): string
    {
        $index = self::seoDefaults()['robots_index'] === '1';

        return $index ? 'index, follow' : 'noindex, nofollow';
    }

    public static function canonicalUrl(?string $path = null): string
    {
        $base = self::seoDefaults()['site_url'];
        $path = $path ?? request()->getPathInfo();

        if ($path === '/' || $path === '') {
            return $base.'/';
        }

        return $base.'/'.ltrim($path, '/');
    }
}
