@extends('layouts.app')

@section('title', $post['title'].' | Elite Physio Clinics')

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
    <nav style="position: sticky; top: 0; z-index: 1000; padding: 20px 48px; display: flex; align-items: center; justify-content: space-between; background: rgba(7,13,14,0.92); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(201,160,66,0.12);" class="blog-nav">
        <a href="{{ route('home') }}" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('logo.png') }}" alt="Elite Physio Clinics" style="width: 40px; height: 40px; border-radius: 50%; box-shadow: 0 0 0 2px rgba(201,160,66,0.4);" />
            <div>
                <div style="font-size: 15px; font-weight: 600; color: #faf6ef; letter-spacing: 0.14em; font-family: 'Cormorant Garamond', serif; line-height: 1.1;">ELITE PHYSIO</div>
                <div class="hide-mobile" style="font-size: 9px; color: rgba(201,160,66,0.75); letter-spacing: 0.3em; text-transform: uppercase;">CLINICS · NORTHAMPTON</div>
            </div>
        </a>
        <div class="hide-mobile" style="display: flex; gap: 36px; align-items: center;">
            @foreach($navLinks as $link)
                <a href="{{ $link['href'] }}" style="font-size: 12px; color: {{ $link['label'] === 'Blog' ? '#c9a042' : 'rgba(250,246,239,0.75)' }}; text-decoration: none; letter-spacing: 0.18em; text-transform: uppercase; font-weight: 500;">{{ $link['label'] }}</a>
            @endforeach
            <a href="{{ route('home') }}#contact" style="font-size: 11px; color: #0a1f13; background: #c9a042; padding: 11px 26px; border-radius: 2px; text-decoration: none; letter-spacing: 0.14em; text-transform: uppercase; font-weight: 700;">Book Now</a>
        </div>
        <a href="{{ route('blog') }}" class="show-mobile-only" style="display: none; font-size: 11px; color: #c9a042; text-decoration: none; letter-spacing: 0.14em; text-transform: uppercase;">All articles</a>
    </nav>

    <main style="padding: 64px 24px 80px; max-width: 780px; margin: 0 auto;">
        <a href="{{ route('blog') }}" style="display: inline-flex; align-items: center; gap: 8px; color: rgba(201,160,66,0.8); text-decoration: none; font-size: 12px; letter-spacing: 0.14em; text-transform: uppercase; margin-bottom: 28px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Back to Blog
        </a>

        <div style="font-size: 11px; color: #c9a042; letter-spacing: 0.28em; text-transform: uppercase; margin-bottom: 16px;">{{ $post['category'] }}</div>
        <h1 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(34px, 5vw, 52px); font-weight: 300; margin: 0 0 24px; line-height: 1.15;">{{ $post['title'] }}</h1>
        <p style="margin: 0 0 28px; font-size: 16px; line-height: 1.7; color: rgba(250,246,239,0.55); font-weight: 300;">{{ $post['excerpt'] }}</p>

        @if(!empty($post['image']))
            <div style="margin: 0 0 40px; border: 1px solid rgba(201,160,66,0.15); overflow: hidden;">
                <img src="{{ asset($post['image']) }}" alt="{{ $post['image_alt'] ?? $post['title'] }}" style="width: 100%; height: auto; display: block; max-height: 420px; object-fit: cover;" />
            </div>
        @endif

        <article style="display: flex; flex-direction: column; gap: 18px; font-size: 15px; line-height: 1.8; color: rgba(250,246,239,0.78);">
            @foreach($post['sections'] as $section)
                @if($section['type'] === 'heading')
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #c9a042; margin: 18px 0 4px; font-weight: 400;">{{ $section['text'] }}</h2>
                @elseif($section['type'] === 'subheading')
                    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 22px; color: #faf6ef; margin: 12px 0 0; font-weight: 500;">{{ $section['text'] }}</h3>
                @elseif($section['type'] === 'paragraph')
                    <p style="margin: 0;">{{ $section['text'] }}</p>
                @elseif($section['type'] === 'list')
                    <ul style="margin: 0; padding-left: 22px; display: flex; flex-direction: column; gap: 8px;">
                        @foreach($section['items'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @elseif($section['type'] === 'image')
                    <figure style="margin: 8px 0 4px; border: 1px solid rgba(201,160,66,0.12); background: rgba(255,255,255,0.03); padding: 16px; text-align: center;">
                        <img src="{{ asset($section['src']) }}" alt="{{ $section['alt'] ?? '' }}" style="max-width: 100%; height: auto; display: inline-block;" loading="lazy" />
                        @if(!empty($section['alt']))
                            <figcaption style="margin-top: 12px; font-size: 12px; color: rgba(250,246,239,0.4); letter-spacing: 0.04em;">{{ $section['alt'] }}</figcaption>
                        @endif
                    </figure>
                @endif
            @endforeach
        </article>

        <div style="margin-top: 48px; padding: 28px 24px; border: 1px solid rgba(201,160,66,0.2); background: rgba(201,160,66,0.06);">
            <div style="font-family: 'Cormorant Garamond', serif; font-size: 26px; color: #faf6ef; margin-bottom: 10px;">Need personalised advice?</div>
            <p style="margin: 0 0 18px; font-size: 14px; color: rgba(250,246,239,0.55); line-height: 1.7;">Our physiotherapists can assess what’s causing your symptoms and build a treatment plan around your goals.</p>
            <a href="{{ route('home') }}#contact" style="display: inline-block; background: #c9a042; color: #0a1f13; padding: 12px 22px; text-decoration: none; font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; font-weight: 700;">Book an appointment</a>
        </div>

        <p style="margin: 28px 0 0; font-size: 12px; color: rgba(250,246,239,0.35); line-height: 1.7;">
            Health information adapted from
            <a href="{{ $post['source_url'] }}" target="_blank" rel="noopener noreferrer" style="color: rgba(201,160,66,0.75);">Bupa UK</a>
            for educational purposes. This is not a substitute for professional medical advice.
        </p>

        @if(count($related))
            <div style="margin-top: 64px; padding-top: 40px; border-top: 1px solid rgba(201,160,66,0.12);">
                <div style="font-size: 11px; color: #c9a042; letter-spacing: 0.28em; text-transform: uppercase; margin-bottom: 20px;">More articles</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="related-grid">
                    @foreach($related as $item)
                        <a href="{{ route('blog.show', $item['slug']) }}" style="display: block; text-decoration: none; border: 1px solid rgba(201,160,66,0.15); overflow: hidden; color: inherit;">
                            @if(!empty($item['image']))
                                <div style="aspect-ratio: 2 / 1; overflow: hidden;">
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['image_alt'] ?? $item['title'] }}" style="width: 100%; height: 100%; object-fit: cover; display: block;" loading="lazy" />
                                </div>
                            @endif
                            <div style="padding: 18px;">
                                <div style="font-size: 10px; color: rgba(201,160,66,0.65); letter-spacing: 0.18em; text-transform: uppercase; margin-bottom: 10px;">{{ $item['category'] }}</div>
                                <div style="font-family: 'Cormorant Garamond', serif; font-size: 22px; color: #faf6ef; line-height: 1.3;">{{ $item['title'] }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <footer style="background: #070d0e; border-top: 1px solid rgba(201,160,66,0.1); padding: 36px 48px;">
        <div style="max-width: 780px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <span style="font-family: 'Cormorant Garamond', serif; font-size: 13px; color: rgba(250,246,239,0.35); letter-spacing: 0.12em;">ELITE PHYSIO CLINICS</span>
            <a href="{{ route('blog') }}" style="font-size: 11px; color: rgba(250,246,239,0.45); text-decoration: none; letter-spacing: 0.14em; text-transform: uppercase;">Back to Blog</a>
        </div>
    </footer>

    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" title="Chat on WhatsApp" style="position: fixed; bottom: 24px; right: 24px; width: 56px; height: 56px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(37,211,102,0.4); z-index: 9999; text-decoration: none;">
        <svg viewBox="0 0 24 24" width="28" height="28" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>
</div>

<style>
@media (max-width: 767px) {
    .blog-nav { padding: 14px 20px !important; }
    .hide-mobile { display: none !important; }
    .show-mobile-only { display: inline-flex !important; }
    .related-grid { grid-template-columns: 1fr !important; }
}
</style>
@endsection
