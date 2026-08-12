@extends('layouts.app')

@section('title', 'Blog | Elite Physio Clinics')

@section('content')
@php
    $whatsappUrl = config('clinic.whatsapp_url');
    $navLinks = [
        ['label' => 'Services', 'href' => route('home').'#services'],
        ['label' => 'About', 'href' => route('home').'#about'],
        ['label' => 'Insurance', 'href' => route('home').'#insurance'],
        ['label' => 'Blog', 'href' => route('blog')],
        ['label' => 'Contact', 'href' => route('home').'#contact'],
    ];
@endphp

<div style="font-family: Outfit, sans-serif; background: #0a1f13; min-height: 100vh; color: #faf6ef;">
    <nav id="main-nav" style="position: sticky; top: 0; left: 0; right: 0; z-index: 1000; padding: 20px 48px; display: flex; align-items: center; justify-content: space-between; background: rgba(7,13,14,0.92); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(201,160,66,0.12);">
        <a href="{{ route('home') }}" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('logo.png') }}" alt="Elite Physio Clinics" style="width: 40px; height: 40px; flex-shrink: 0; border-radius: 50%; box-shadow: 0 0 0 2px rgba(201,160,66,0.4), 0 0 12px rgba(201,160,66,0.15);" />
            <div>
                <div style="font-size: 15px; font-weight: 600; color: #faf6ef; letter-spacing: 0.14em; font-family: 'Cormorant Garamond', serif; line-height: 1.1;">ELITE PHYSIO</div>
                <div class="hide-mobile" style="font-size: 9px; color: rgba(201,160,66,0.75); letter-spacing: 0.3em; font-family: Outfit, sans-serif; text-transform: uppercase;">CLINICS · NORTHAMPTON</div>
            </div>
        </a>
        <div class="hide-mobile" style="display: flex; gap: 36px; align-items: center;">
            @foreach($navLinks as $link)
                <a href="{{ $link['href'] }}" style="font-size: 12px; color: {{ $link['label'] === 'Blog' ? '#c9a042' : 'rgba(250,246,239,0.75)' }}; text-decoration: none; letter-spacing: 0.18em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 500;">{{ $link['label'] }}</a>
            @endforeach
            <a href="{{ route('home') }}#contact" style="font-size: 11px; color: #0a1f13; background: #c9a042; padding: 11px 26px; border-radius: 2px; text-decoration: none; letter-spacing: 0.14em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 700;">Book Now</a>
        </div>
        <a href="{{ route('home') }}" class="show-mobile-only" style="display: none; font-size: 11px; color: #c9a042; text-decoration: none; letter-spacing: 0.14em; text-transform: uppercase;">Home</a>
    </nav>

    <main style="padding: 72px 24px 96px; max-width: 1100px; margin: 0 auto;">
        <div style="text-align: center; max-width: 820px; margin: 0 auto 56px;">
            <div style="font-size: 11px; color: #c9a042; letter-spacing: 0.35em; text-transform: uppercase; font-weight: 600; margin-bottom: 18px;">── Health Insights</div>
            <h1 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(36px, 5vw, 58px); font-weight: 300; margin: 0 0 20px; line-height: 1.15; color: #faf6ef;">
                {{ $sectionTitle }}
            </h1>
            <p style="margin: 0; font-size: 16px; line-height: 1.7; color: rgba(250,246,239,0.55); font-weight: 300;">
                {{ $sectionIntro }}
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;" class="blog-grid">
            @foreach($posts as $index => $post)
                <a href="{{ route('blog.show', $post['slug']) }}" class="blog-card" style="display: flex; flex-direction: column; text-decoration: none; color: inherit; border: 1px solid rgba(201,160,66,0.15); background: rgba(250,246,239,0.02); overflow: hidden; min-height: 360px; transition: border-color 0.3s ease, background 0.3s ease;" onmouseenter="this.style.borderColor='rgba(201,160,66,0.45)'; this.style.background='rgba(255,255,255,0.04)'" onmouseleave="this.style.borderColor='rgba(201,160,66,0.15)'; this.style.background='rgba(250,246,239,0.02)'">
                    @if(!empty($post['image']))
                        <div style="aspect-ratio: 2 / 1; overflow: hidden; background: rgba(0,0,0,0.25);">
                            <img src="{{ asset($post['image']) }}" alt="{{ $post['image_alt'] ?? $post['title'] }}" style="width: 100%; height: 100%; object-fit: cover; display: block; filter: saturate(0.92) contrast(1.05);" loading="lazy" />
                        </div>
                    @endif
                    <div style="padding: 24px; display: flex; flex-direction: column; flex: 1;">
                        <div style="font-size: 10px; color: rgba(201,160,66,0.7); letter-spacing: 0.22em; text-transform: uppercase; margin-bottom: 14px;">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }} · {{ $post['category'] }}</div>
                        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 400; color: #faf6ef; margin: 0 0 14px; line-height: 1.25;">{{ $post['title'] }}</h2>
                        <p style="margin: 0 0 22px; font-size: 14px; line-height: 1.7; color: rgba(250,246,239,0.5); font-weight: 300; flex: 1;">{{ $post['excerpt'] }}</p>
                        <span style="font-size: 11px; letter-spacing: 0.16em; text-transform: uppercase; color: #c9a042; font-weight: 600;">Read article →</span>
                    </div>
                </a>
            @endforeach
        </div>
    </main>

    <footer style="background: #070d0e; border-top: 1px solid rgba(201,160,66,0.1); padding: 36px 48px;">
        <div style="max-width: 1100px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('logo.png') }}" alt="Elite Physio Clinics" style="width: 28px; height: 28px; opacity: 0.7; border-radius: 50%;" />
                <span style="font-family: 'Cormorant Garamond', serif; font-size: 13px; color: rgba(250,246,239,0.35); letter-spacing: 0.12em;">ELITE PHYSIO CLINICS</span>
            </div>
            <a href="{{ route('home') }}#contact" style="font-size: 11px; color: #0a1f13; background: #c9a042; padding: 10px 20px; text-decoration: none; letter-spacing: 0.14em; text-transform: uppercase; font-weight: 700;">Book an appointment</a>
        </div>
    </footer>

    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" title="Chat on WhatsApp" style="position: fixed; bottom: 24px; right: 24px; width: 56px; height: 56px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(37,211,102,0.4); z-index: 9999; text-decoration: none;">
        <svg viewBox="0 0 24 24" width="28" height="28" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>
</div>

<style>
@media (max-width: 900px) {
    .blog-grid { grid-template-columns: 1fr !important; }
}
@media (max-width: 767px) {
    #main-nav { padding: 14px 20px !important; }
    .hide-mobile { display: none !important; }
    .show-mobile-only { display: inline-flex !important; }
}
</style>
@endsection
