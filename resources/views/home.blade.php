@extends('layouts.app')

@section('content')
@php
    $whatsappUrl = config('clinic.whatsapp_url');
    $navLinks = ['Services', 'About', 'Insurance', 'Contact'];
    $services = [
        ['title' => 'Back Pain & Sciatica', 'desc' => 'Expert treatment for lumbar disc conditions, spinal stenosis, and sciatic nerve pain along the full nerve pathway.'],
        ['title' => 'Neck Pain & Whiplash', 'desc' => 'Comprehensive cervical spine assessment and mobilisation for acute and chronic neck conditions.'],
        ['title' => 'Sports Injuries', 'desc' => 'From acute ligament sprains to chronic overuse conditions — treatment for athletes at every level.'],
        ['title' => 'Arthritis Management', 'desc' => 'Evidence-based strategies to reduce pain, improve joint mobility, and maintain quality of life.'],
        ['title' => 'Post-Surgical Rehab', 'desc' => 'Structured progressive rehabilitation programs following orthopaedic and spinal surgery.'],
        ['title' => 'Frozen Shoulder', 'desc' => 'Specialised capsular mobilisation and graded stretching for adhesive capsulitis at all stages.'],
        ['title' => 'Tendon Injuries', 'desc' => "Targeted loading therapy for tennis elbow, golfer's elbow, and tendinopathy conditions."],
        ['title' => 'Knee & Ankle', 'desc' => 'Biomechanical assessment and targeted rehabilitation for lower limb conditions and instability.'],
    ];
    $pediatricServices = [
        ['title' => 'Head Turning Preference & Torticollis', 'desc' => 'Assessment and treatment for infant neck tightness, head turning preference, and associated movement asymmetry.'],
        ['title' => 'Flat Head Syndrome', 'desc' => 'Management of Brachycephaly and Plagiocephaly through positioning guidance, physiotherapy, and developmental support.'],
        ['title' => 'Delayed Developmental Milestones', 'desc' => 'Support for infants and children experiencing delays in motor skills such as rolling, sitting, crawling, and walking.'],
        ['title' => 'Cerebral Palsy & Birth-Related Conditions', 'desc' => 'Individualized therapy programs to improve movement control, strength, and functional independence.'],
        ['title' => 'Balance & Coordination Difficulties', 'desc' => 'Targeted rehabilitation for Developmental Coordination Disorder (DCD) and other motor coordination challenges.'],
        ['title' => 'Chromosomal, Genetic & Neurological Conditions', 'desc' => 'Specialist physiotherapy care supporting movement, posture, and development in complex conditions.'],
        ['title' => 'Positional Talipes (Clubfoot)', 'desc' => 'Early intervention and therapeutic management to improve foot positioning and mobility.'],
        ['title' => 'Gait Disorders', 'desc' => 'Assessment and treatment for walking abnormalities including flat feet, intoeing, and out-toeing.'],
        ['title' => 'Musculoskeletal Conditions in Children', 'desc' => 'Management of growth-related and orthopaedic conditions affecting bones, joints, and muscles.'],
        ['title' => 'Osgood-Schlatter Disease', 'desc' => 'Treatment for activity-related knee pain common in growing adolescents.'],
        ['title' => "Sever's Disease", 'desc' => 'Rehabilitation strategies to relieve heel pain associated with growth plate irritation.'],
        ['title' => 'Osteochondritis Dissecans', 'desc' => 'Specialised care for joint cartilage and bone conditions affecting young athletes.'],
    ];
    $stats = [
        ['value' => 20, 'suffix' => '+', 'label' => 'Years Experience', 'sub' => 'NHS & Private Practice'],
        ['value' => 9, 'suffix' => '+', 'label' => 'Insurance Partners', 'sub' => 'AXA, Aviva, WPA & more'],
        ['value' => 100, 'suffix' => '%', 'label' => 'Personalised Care', 'sub' => 'Tailored to every patient'],
    ];
    $insurance = [
        ['name' => 'AXA Health', 'logo' => 'insurance/AXA_Health.webp'],
        ['name' => 'Aviva', 'logo' => 'insurance/Aviva.svg'],
        ['name' => 'Vitality', 'logo' => 'insurance/Vitality.svg'],
        ['name' => 'WPA', 'logo' => 'insurance/WPA.svg'],
        ['name' => 'IPRS Health', 'logo' => 'insurance/IPRS_Health.png'],
        ['name' => 'Cigna', 'logo' => 'insurance/Cigna.svg'],
        ['name' => 'HCML', 'logo' => 'insurance/HCML.png'],
        ['name' => 'Treatment Network', 'logo' => 'insurance/Treatment_Network.svg'],
        ['name' => 'Speed Medical', 'logo' => 'insurance/Speed_Medical.png'],
    ];
    $credentials = [
        ['label' => 'Doctor of Physiotherapy (DPT)', 'highlight' => true],
        ['label' => 'MSc Physiotherapy — Coventry University', 'highlight' => false],
        ['label' => 'Chartered Physiotherapist (MCSP)', 'highlight' => false],
        ['label' => '20+ Years Musculoskeletal Specialist', 'highlight' => true],
        ['label' => 'Post-Graduate Musculoskeletal Training', 'highlight' => false],
        ['label' => "Specialist — Children's Physiotherapy", 'highlight' => false],
    ];
    $clinicImages = [
        ['src' => 'clinic/treatment-room-1.jpg', 'label' => 'Treatment Room'],
        ['src' => 'clinic/waiting-room.jpg', 'label' => 'Waiting Area'],
        ['src' => 'clinic/hallway.jpg', 'label' => 'Our Clinic'],
        ['src' => 'clinic/reception.jpg', 'label' => 'Reception'],
        ['src' => 'clinic/treatment-room-2.jpg', 'label' => 'Treatment Suite'],
    ];
    $galleryImages = array_merge($clinicImages, $clinicImages);
    $pillars = [
        ['title' => 'NHS-Trained Expertise', 'desc' => 'Over five years within the National Health Service — clinical precision you can trust.', 'icon' => 'shield'],
        ['title' => 'Truly Personal Care', 'desc' => 'No generic protocols. Every plan is crafted around your specific condition and goals.', 'icon' => 'heart'],
        ['title' => 'Recognised Qualifications', 'desc' => 'DPT-qualified, MCSP registered, and accepted by 9 major insurance providers.', 'icon' => 'award'],
        ['title' => 'Flexible Hours', 'desc' => "Evening and Saturday appointments — because your recovery shouldn't wait.", 'icon' => 'star'],
    ];
    $fmtTime = function ($t) {
        [$h, $m] = array_map('intval', explode(':', $t));
        $p = $h >= 12 ? 'PM' : 'AM';
        $h12 = $h === 0 ? 12 : ($h > 12 ? $h - 12 : $h);
        return $h12 . ':' . str_pad((string) $m, 2, '0', STR_PAD_LEFT) . ' ' . $p;
    };
    $hoursLines = [];
    $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $i = 0;
    while ($i < count($dayNames)) {
        $h = $clinicHours[$dayNames[$i]] ?? null;
        if (!$h) { $i++; continue; }
        $j = $i;
        while ($j + 1 < count($dayNames)) {
            $next = $clinicHours[$dayNames[$j + 1]] ?? null;
            if ($next && $next['start'] === $h['start'] && $next['end'] === $h['end']) {
                $j++;
            } else {
                break;
            }
        }
        $label = $i === $j ? substr($dayNames[$i], 0, 3) : substr($dayNames[$i], 0, 3) . ' – ' . substr($dayNames[$j], 0, 3);
        $hoursLines[] = $label . ' · ' . $fmtTime($h['start']) . ' – ' . $fmtTime($h['end']);
        $i = $j + 1;
    }
    $hoursDisplay = implode("\n", $hoursLines) ?: 'Contact us for hours';
@endphp

<div style="font-family: Outfit, sans-serif; background: #0a1f13;">
    {{-- NavBar --}}
    <nav id="main-nav" style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 24px 48px; display: flex; align-items: center; justify-content: space-between; background: transparent; backdrop-filter: none; border-bottom: none; transition: all 0.4s cubic-bezier(0.25,0.46,0.45,0.94);">
        <a href="#" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('logo.png') }}" alt="Elite Physio Clinics" style="width: 44px; height: 44px; flex-shrink: 0; border-radius: 50%; box-shadow: 0 0 0 2px rgba(201,160,66,0.4), 0 0 12px rgba(201,160,66,0.15); transition: filter 0.3s ease;" />
            <div>
                <div class="nav-logo-title" style="font-size: 15px; font-weight: 600; color: #faf6ef; letter-spacing: 0.14em; font-family: 'Cormorant Garamond', serif; line-height: 1.1;">ELITE PHYSIO</div>
                <div class="nav-logo-sub hide-mobile" style="font-size: 9px; color: rgba(201,160,66,0.75); letter-spacing: 0.3em; font-family: Outfit, sans-serif; text-transform: uppercase;">CLINICS · NORTHAMPTON</div>
            </div>
        </a>
        <div class="hide-mobile" style="display: flex; gap: 40px; align-items: center;">
            @foreach($navLinks as $link)
                <a href="#{{ strtolower($link) }}" class="nav-link" style="font-size: 12px; color: rgba(250,246,239,0.75); text-decoration: none; letter-spacing: 0.18em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 500;">{{ $link }}</a>
            @endforeach
            <a href="#contact" class="btn-primary" style="font-size: 11px; color: #0a1f13; background: #c9a042; padding: 11px 26px; border-radius: 2; text-decoration: none; letter-spacing: 0.14em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 700;">Book Now</a>
        </div>
        <button type="button" id="menu-toggle" class="show-mobile-only" style="background: none; border: none; cursor: pointer; padding: 4px; color: #faf6ef; display: none; align-items: center;" aria-label="Toggle menu">
            <span id="menu-icon-open"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#faf6ef" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg></span>
            <span id="menu-icon-close" style="display: none;"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#faf6ef" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></span>
        </button>
    </nav>

    <div id="mobile-menu" style="position: fixed; inset: 0; top: 56px; z-index: 999; background: rgba(6,14,9,0.98); backdrop-filter: blur(24px); display: none; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
        <div style="width: 1px; height: 40px; background: rgba(201,160,66,0.3); margin-bottom: 16px;"></div>
        @foreach($navLinks as $link)
            <a href="#{{ strtolower($link) }}" style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 300; color: #faf6ef; text-decoration: none; letter-spacing: 0.08em; padding: 10px 0; display: block;">{{ $link }}</a>
        @endforeach
        <div style="width: 40px; height: 1px; background: rgba(201,160,66,0.3); margin: 20px 0;"></div>
        <a href="#contact" style="display: inline-flex; align-items: center; gap: 10px; background: #c9a042; color: #0a1f13; padding: 14px 36px; border-radius: 2; text-decoration: none; font-size: 13px; letter-spacing: 0.16em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 700;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            Book Now
        </a>
        <div style="margin-top: 24px; font-size: 11px; color: rgba(250,246,239,0.25); letter-spacing: 0.12em; font-family: Outfit, sans-serif;">+44 333 577 9553</div>
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; font-size: 11px; color: rgba(37,211,102,0.7); letter-spacing: 0.12em; font-family: Outfit, sans-serif; text-decoration: none; transition: color 0.2s;" onmouseenter="this.style.color='#25D366'" onmouseleave="this.style.color='rgba(37,211,102,0.7)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
            WhatsApp
        </a>
    </div>

    {{-- Hero --}}
    <section class="hero-section">
        @include('partials.hero-background')
        <div class="hero-vignette"></div>
        <div class="hero-content">
            <div class="hero-eyebrow" style="font-size: 11px; color: rgba(201,160,66,0.8); letter-spacing: 0.35em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 500; margin-bottom: 24px; text-align: center;">Chartered Physiotherapy · Northampton</div>
            <div style="display: flex; gap: 6px; justify-content: center; overflow: hidden;">
                @foreach(str_split('ELITE') as $letter)
                    <span class="hero-letter" style="display: inline-block; font-family: 'Cormorant Garamond', serif; font-size: clamp(72px, 10vw, 130px); font-weight: 300; color: #faf6ef; letter-spacing: 0.22em; line-height: 1; text-shadow: 0 0 80px rgba(201,160,66,0.15);">{{ $letter }}</span>
                @endforeach
            </div>
            <div class="hero-divider" style="width: 260px; height: 1px; margin: 20px 0; background: linear-gradient(90deg, transparent, #c9a042 20%, #e8c96d 50%, #c9a042 80%, transparent); transform-origin: center;"></div>
            <div class="hero-subtitle" style="font-family: 'Cormorant Garamond', serif; font-size: clamp(16px, 2.5vw, 26px); font-weight: 400; color: #c9a042; letter-spacing: 0.62em; text-transform: uppercase; margin-bottom: 28px;">PHYSIO CLINICS</div>
            <p class="hero-tagline hide-mobile" style="font-size: 15px; color: rgba(250,246,239,0.55); font-family: Outfit, sans-serif; font-weight: 300; letter-spacing: 0.04em; line-height: 1.8; text-align: center; max-width: 460px; margin-bottom: 40px;">
                Personalised physiotherapy to help you recover from injuries, manage pain, and enhance your well-being.
            </p>
            <div class="hero-ctas" style="display: flex; flex-direction: row; gap: 16px; align-items: center; width: auto; max-width: none;">
                <a href="#contact" class="btn-primary" style="display: inline-flex; align-items: center; justify-content: center; gap: 9px; background: #c9a042; color: #0a1f13; padding: 14px 32px; width: auto; border-radius: 2; text-decoration: none; font-size: 12px; letter-spacing: 0.16em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 700;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Book Now
                </a>
                <a href="#services" class="btn-ghost" style="display: inline-flex; align-items: center; justify-content: center; gap: 9px; background: transparent; color: #faf6ef; padding: 14px 32px; width: auto; border-radius: 2; text-decoration: none; font-size: 12px; letter-spacing: 0.16em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 500; border: 1px solid rgba(250,246,239,0.3);">
                    Explore Services
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
        </div>
        <div style="position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 5px;">
            <span style="font-size: 9px; color: rgba(201,160,66,0.45); letter-spacing: 0.25em; text-transform: uppercase; font-family: Outfit, sans-serif;">Scroll</span>
            <div style="animation: bounce-down 2s ease-in-out infinite;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(201,160,66,0.45)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </div>
        </div>
        <div class="hide-mobile" style="position: absolute; bottom: 28px; right: 40px; z-index: 2; display: flex; align-items: center; gap: 10px;">
            <div style="width: 24px; height: 1px; background: rgba(201,160,66,0.35);"></div>
            <span style="font-size: 10px; color: rgba(201,160,66,0.5); letter-spacing: 0.2em; text-transform: uppercase; font-family: Outfit, sans-serif;">Led by Wafaa Ibrahim · DPT · MSc</span>
        </div>
    </section>

    {{-- Stats --}}
    <section style="background: #070d0e; border-top: 1px solid rgba(201,160,66,0.12); border-bottom: 1px solid rgba(201,160,66,0.12);">
        <div class="stats-grid" style="max-width: 960px; margin: 0 auto; display: grid; grid-template-columns: repeat(3, 1fr);">
            @foreach($stats as $i => $s)
                <div class="stat-cell" style="border-right: {{ $i < 2 ? '1px solid rgba(201,160,66,0.1)' : 'none' }}; border-bottom: none;">
                    <div style="text-align: center; padding: 48px 24px;">
                        <div class="shimmer-text stat-value" data-stat-value="{{ $s['value'] }}" data-stat-suffix="{{ $s['suffix'] }}" style="font-family: 'Cormorant Garamond', serif; font-size: clamp(60px, 7vw, 88px); font-weight: 300; line-height: 1; margin-bottom: 10px;">0{{ $s['suffix'] }}</div>
                        <div style="font-size: 15px; font-weight: 600; color: #faf6ef; letter-spacing: 0.06em; margin-bottom: 5px; font-family: Outfit, sans-serif;">{{ $s['label'] }}</div>
                        <div style="font-size: 11px; color: rgba(250,246,239,0.38); letter-spacing: 0.12em; font-family: Outfit, sans-serif; text-transform: uppercase;">{{ $s['sub'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Services --}}
    <section id="services" style="position: relative; overflow: hidden; padding: 120px 48px;">
        <video autoplay loop muted playsinline style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;">
            <source src="{{ asset('hero-video.mp4') }}" type="video/mp4" />
        </video>
        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(6,14,9,0.88) 0%, rgba(10,31,19,0.78) 50%, rgba(6,14,9,0.92) 100%); z-index: 1;"></div>
        <div style="max-width: 1240px; margin: 0 auto; position: relative; z-index: 2;">
            <div class="services-header" style="display: flex; flex-direction: row; justify-content: space-between; align-items: flex-end; gap: 40px; margin-bottom: 56px;">
                <div>
                    <div style="font-size: 11px; color: rgba(250,246,239,0.7); letter-spacing: 0.35em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 600; margin-bottom: 14px;">── SPECIALIST CARE</div>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(48px, 6vw, 72px); font-weight: 300; color: #faf6ef; line-height: 1.05; margin: 0;">Conditions<br /><em style="font-style: italic; color: #e8c96d;">We Treat</em></h2>
                </div>
                <div class="services-intro" style="max-width: 360px; font-size: 14px; line-height: 1.85; color: rgba(250,246,239,0.7); font-family: Outfit, sans-serif; font-weight: 300;">
                    Evidence-based physiotherapy for a comprehensive range of musculoskeletal and neurological conditions, delivered with genuine personal care.
                </div>
            </div>
            <div style="display: flex; gap: 0; margin-bottom: 12px; border-bottom: 1px solid rgba(201,160,66,0.12);">
                <button type="button" id="tab-adult" style="flex: 1; position: relative; padding: 16px 24px; background: transparent; border: none; color: #faf6ef; font-family: Outfit, sans-serif; font-size: 13px; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; cursor: pointer; transition: color 0.3s;">Adult Conditions<div id="tab-underline-adult" style="position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: #c9a042;"></div></button>
                <button type="button" id="tab-paediatric" style="flex: 1; position: relative; padding: 16px 24px; background: transparent; border: none; color: rgba(250,246,239,0.35); font-family: Outfit, sans-serif; font-size: 13px; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; cursor: pointer; transition: color 0.3s;">Children's Conditions<div id="tab-underline-paediatric" style="position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: #c9a042; display: none;"></div></button>
            </div>
            <div id="services-swipe-hint" class="show-mobile-only" style="display: none; text-align: center; padding: 6px 0; font-size: 11px; color: rgba(250,246,239,0.25); font-family: Outfit, sans-serif; letter-spacing: 0.1em;">Swipe to switch</div>
            <div id="services-panel-area" style="overflow: hidden; margin-top: 32px;">
                <div id="services-panel-adult">
                    <div class="services-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 3px;">
                        @foreach($services as $i => $s)
                            <div class="service-card" style="padding: 32px 28px; background: rgba(6,14,9,0.55); backdrop-filter: blur(12px); border: 1px solid rgba(201,160,66,0.1); position: relative; overflow: hidden;">
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, #e8c96d, rgba(201,160,66,0));"></div>
                                <div style="font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 300; color: rgba(201,160,66,0.2); margin-bottom: 12px; line-height: 1;">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</div>
                                <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 500; color: #faf6ef; margin: 0 0 10px; line-height: 1.25;">{{ $s['title'] }}</h3>
                                <p style="font-size: 13px; color: rgba(250,246,239,0.55); line-height: 1.75; margin: 0; font-family: Outfit, sans-serif; font-weight: 300;">{{ $s['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div id="services-panel-paediatric" style="display: none;">
                    <div class="services-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 3px;">
                        @foreach($pediatricServices as $i => $s)
                            <div class="service-card" style="padding: 32px 28px; background: rgba(6,14,9,0.55); backdrop-filter: blur(12px); border: 1px solid rgba(201,160,66,0.1); position: relative; overflow: hidden;">
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, #e8c96d, rgba(201,160,66,0));"></div>
                                <div style="font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 300; color: rgba(201,160,66,0.2); margin-bottom: 12px; line-height: 1;">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</div>
                                <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 500; color: #faf6ef; margin: 0 0 10px; line-height: 1.25;">{{ $s['title'] }}</h3>
                                <p style="font-size: 13px; color: rgba(250,246,239,0.55); line-height: 1.75; margin: 0; font-family: Outfit, sans-serif; font-weight: 300;">{{ $s['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Philosophy --}}
    <section style="background: #0a1f13; padding: 80px 48px; overflow: hidden;">
        <div class="philosophy-inner" style="max-width: 1240px; margin: 0 auto; display: flex; flex-direction: row; align-items: center; gap: 60px; text-align: left;">
            <div style="flex-shrink: 0;">
                <img src="{{ asset('logo.png') }}" alt="Elite Physio Clinics" class="philosophy-logo" style="width: 72px; height: 72px; opacity: 0.9; border-radius: 50%; box-shadow: 0 0 0 2.5px rgba(201,160,66,0.45), 0 0 18px rgba(201,160,66,0.2); filter: drop-shadow(0 0 8px rgba(201,160,66,0.25));" />
            </div>
            <blockquote style="font-family: 'Cormorant Garamond', serif; font-size: clamp(22px, 3vw, 34px); font-weight: 300; font-style: italic; color: rgba(250,246,239,0.88); line-height: 1.55; margin: 0;">
                "We believe outstanding physiotherapy is built on clinical precision, genuine human connection, and a relentless commitment to getting you back to what you love."
            </blockquote>
            <div class="philosophy-author-desktop hide-mobile" style="flex-shrink: 0; text-align: right;">
                <div style="font-size: 12px; color: #c9a042; letter-spacing: 0.15em; font-family: Outfit, sans-serif;">Wafaa Ibrahim</div>
                <div style="font-size: 11px; color: rgba(250,246,239,0.35); letter-spacing: 0.1em; font-family: Outfit, sans-serif; margin-top: 4px;">Founder & Lead Physiotherapist</div>
            </div>
        </div>
        <div class="philosophy-author-mobile show-mobile-only" style="display: none; text-align: center; margin-top: 28px;">
            <div style="font-size: 12px; color: #c9a042; letter-spacing: 0.15em; font-family: Outfit, sans-serif;">Wafaa Ibrahim</div>
            <div style="font-size: 11px; color: rgba(250,246,239,0.35); letter-spacing: 0.1em; font-family: Outfit, sans-serif; margin-top: 4px;">Founder & Lead Physiotherapist</div>
        </div>
    </section>

    {{-- About --}}
    <section id="about" style="background: #f5f0e8; padding: 120px 48px;">
        <div class="about-grid" style="max-width: 1240px; margin: 0 auto; display: grid; grid-template-columns: 5fr 7fr; gap: 80px; align-items: center;">
            <div style="position: relative; max-width: none; margin: 0;">
                <div style="aspect-ratio: 3/4; position: relative; overflow: hidden;">
                    <img src="{{ asset('dr-wafaa.webp') }}" alt="Wafaa Ibrahim" style="width: 100%; height: 100%; object-fit: cover; object-position: center top;" />
                    <div style="position: absolute; top: -1px; right: -1px; width: 44px; height: 44px; border-top: 2px solid #c9a042; border-right: 2px solid #c9a042;"></div>
                    <div style="position: absolute; bottom: -1px; left: -1px; width: 44px; height: 44px; border-bottom: 2px solid #c9a042; border-left: 2px solid #c9a042;"></div>
                </div>
                <div class="about-badge" style="position: absolute; bottom: -24px; right: -24px; background: #c9a042; padding: 18px 22px; border-radius: 2; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
                    <div style="font-family: 'Cormorant Garamond', serif; font-size: 36px; font-weight: 600; color: #0a1f13; line-height: 1;">20+</div>
                    <div style="font-size: 9px; color: rgba(10,31,19,0.7); letter-spacing: 0.12em; text-transform: uppercase; font-family: Outfit, sans-serif; margin-top: 4px;">Years Experience</div>
                </div>
            </div>
            <div style="padding-top: 0;">
                <div style="font-size: 11px; color: #c9a042; letter-spacing: 0.35em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 600; margin-bottom: 18px;">── MEET YOUR THERAPIST</div>
                <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(40px, 5vw, 60px); font-weight: 300; color: #0a1f13; line-height: 1.05; margin: 0 0 24px;">The Expert<br />Behind <em style="font-style: italic;">Your Recovery</em></h2>
                <div style="width: 44px; height: 1px; background: #c9a042; margin-bottom: 24px;"></div>
                <p style="font-size: 14px; line-height: 1.9; color: #3d5a50; font-family: Outfit, sans-serif; font-weight: 300; margin-bottom: 32px;">
                    Wafaa Ibrahim is a Chartered Physiotherapist with over 20 years of experience as a Musculoskeletal specialist. Holding both a Doctor of Physiotherapy and a Master's degree from Coventry University, she brings world-class clinical expertise to every patient encounter.
                </p>
                <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 36px;">
                    @foreach($credentials as $c)
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="{{ $c['highlight'] ? '#c9a042' : 'rgba(45,106,79,0.6)' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                            <span style="font-size: 13px; color: {{ $c['highlight'] ? '#0a1f13' : '#3d5a50' }}; font-family: Outfit, sans-serif; font-weight: {{ $c['highlight'] ? 500 : 400 }};">{{ $c['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <a href="#contact" class="btn-primary" style="display: inline-flex; align-items: center; gap: 10px; background: #0a1f13; color: #faf6ef; padding: 15px 32px; border-radius: 2; text-decoration: none; font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 600;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Book a Consultation
                </a>
            </div>
        </div>
    </section>

    {{-- Clinic Gallery --}}
    <section style="background: #0a1f13; padding: 80px 0; border-top: 1px solid rgba(201,160,66,0.1); border-bottom: 1px solid rgba(201,160,66,0.1);">
        <div style="text-align: center; margin-bottom: 52px; padding: 0 20px;">
            <div style="font-size: 11px; color: #c9a042; letter-spacing: 0.35em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 600; margin-bottom: 16px;">── OUR CLINIC</div>
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(40px, 5vw, 56px); font-weight: 300; color: #faf6ef; margin: 0; line-height: 1.1;">Your <em style="color: #c9a042;">Environment</em></h2>
        </div>
        <div style="position: relative; overflow: hidden;">
            <div class="gallery-fade-left" style="position: absolute; top: 0; bottom: 0; left: 0; width: 80px; background: linear-gradient(to right, #0a1f13, transparent); z-index: 2; pointer-events: none;"></div>
            <div class="gallery-fade-right" style="position: absolute; top: 0; bottom: 0; right: 0; width: 80px; background: linear-gradient(to left, #0a1f13, transparent); z-index: 2; pointer-events: none;"></div>
            <div class="gallery-track" style="display: flex; gap: 20px; animation: marquee 35s linear infinite; width: fit-content;">
                @foreach($galleryImages as $img)
                    <div class="gallery-item" style="position: relative; width: 320px; height: 220px; flex-shrink: 0; border-radius: 3px; overflow: hidden; border: 1px solid rgba(201,160,66,0.15);">
                        <img src="{{ asset($img['src']) }}" alt="{{ $img['label'] }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why --}}
    <section style="background: #0f2a1a; padding: 120px 48px;">
        <div style="max-width: 1240px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 72px;">
                <div style="font-size: 11px; color: #c9a042; letter-spacing: 0.35em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 600; margin-bottom: 16px;">── OUR DIFFERENCE</div>
                <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(40px, 5vw, 60px); font-weight: 300; color: #faf6ef; margin: 0;">Why Patients Choose <em>Elite</em></h2>
            </div>
            <div class="why-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2px;">
                @foreach($pillars as $p)
                    <div style="padding: 44px 32px; background: rgba(255,255,255,0.02); border: 1px solid rgba(201,160,66,0.1); position: relative;">
                        <div style="width: 40px; height: 40px; border: 1px solid rgba(201,160,66,0.25); display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                            @if($p['icon'] === 'shield')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                            @elseif($p['icon'] === 'heart')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            @elseif($p['icon'] === 'award')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endif
                        </div>
                        <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 500; color: #faf6ef; margin: 0 0 10px; line-height: 1.2;">{{ $p['title'] }}</h3>
                        <p style="font-size: 12px; color: rgba(250,246,239,0.4); line-height: 1.7; margin: 0; font-family: Outfit, sans-serif; font-weight: 300;">{{ $p['desc'] }}</p>
                        <div style="position: absolute; top: 0; left: 0; width: 28px; height: 2px; background: #c9a042;"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Insurance --}}
    <section id="insurance" style="background: #070d0e; padding: 100px 48px; border-top: 1px solid rgba(201,160,66,0.1);">
        <div style="max-width: 1000px; margin: 0 auto; text-align: center;">
            <div style="margin-bottom: 40px;">
                <div style="font-size: 11px; color: #c9a042; letter-spacing: 0.35em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 600; margin-bottom: 16px;">── REGISTERED PROVIDER</div>
                <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(32px, 4vw, 48px); font-weight: 300; color: #faf6ef; margin: 0;">Accepted Insurance Partners</h2>
            </div>
            <div style="width: 100%; height: 1px; background: linear-gradient(90deg, transparent, rgba(201,160,66,0.3) 20%, rgba(201,160,66,0.3) 80%, transparent); margin-bottom: 48px;"></div>
            <div class="insurance-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; max-width: 900px; margin: 0 auto;">
                @foreach($insurance as $ins)
                    <div class="insurance-card" style="padding: 28px 24px; border: 1px solid rgba(201,160,66,0.12); background: rgba(255,255,255,0.02); display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; cursor: default; min-height: 80px;" onmouseenter="this.style.borderColor='rgba(201,160,66,0.45)'; this.style.background='rgba(255,255,255,0.06)'; this.querySelector('img').style.opacity='0.9'" onmouseleave="this.style.borderColor='rgba(201,160,66,0.12)'; this.style.background='rgba(255,255,255,0.02)'; this.querySelector('img').style.opacity='0.6'">
                        <img src="{{ asset($ins['logo']) }}" alt="{{ $ins['name'] }}" class="insurance-logo" style="max-width: 130px; max-height: 44px; object-fit: contain; filter: brightness(0) invert(1); opacity: 0.6; transition: opacity 0.3s ease;" onmouseenter="this.style.opacity='0.9'" onmouseleave="this.style.opacity='0.6'" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contact --}}
    <section id="contact" style="background: #0a1f13; padding: 120px 48px;">
        <div style="max-width: 1240px; margin: 0 auto;">
            <div class="contact-header" style="margin-bottom: 72px;">
                <div style="font-size: 11px; color: #c9a042; letter-spacing: 0.35em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 600; margin-bottom: 16px;">── GET IN TOUCH</div>
                <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: flex-end; gap: 20px;">
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(48px, 6vw, 72px); font-weight: 300; color: #faf6ef; margin: 0; line-height: 1;">Begin Your<br /><em style="color: #c9a042;">Recovery</em></h2>
                    <div class="contact-intro hide-mobile" style="max-width: 360px; font-size: 14px; color: rgba(250,246,239,0.45); font-family: Outfit, sans-serif; font-weight: 300; line-height: 1.8;">
                        Ready to take the first step? Reach out to book your initial assessment with Wafaa Ibrahim.
                    </div>
                </div>
            </div>
            <div class="contact-grid" style="display: grid; grid-template-columns: 1fr 1.4fr; gap: 80px;">
                <div>
                    <div style="display: flex; flex-direction: column; gap: 32px;">
                        @php
                            $contactDetails = [
                                ['icon' => 'map', 'label' => 'Location', 'value' => "Mare Fair, Sol Central\nGround Floor, Unit 3\nNorthampton NN1 1SR"],
                                ['icon' => 'phone', 'label' => 'Phone', 'value' => '+44 333 577 9553', 'href' => 'tel:+443335779553'],
                                ['icon' => 'whatsapp', 'label' => 'WhatsApp', 'value' => '+44 7405 825954', 'href' => $whatsappUrl],
                                ['icon' => 'mail', 'label' => 'Email', 'value' => 'elitephysioclinics@gmail.com', 'href' => 'mailto:elitephysioclinics@gmail.com'],
                                ['icon' => 'clock', 'label' => 'Hours', 'value' => $hoursDisplay],
                            ];
                        @endphp
                        @foreach($contactDetails as $detail)
                            @if(!empty($detail['href']))
                                <a href="{{ $detail['href'] }}" @if($detail['label'] === 'WhatsApp') target="_blank" rel="noopener noreferrer" @endif style="display: flex; gap: 16px; align-items: flex-start; text-decoration: none; color: inherit; cursor: pointer;" onmouseenter="this.style.opacity='0.8'; this.querySelector('.contact-icon-box').style.borderColor='rgba(201,160,66,0.5)'" onmouseleave="this.style.opacity='1'; this.querySelector('.contact-icon-box').style.borderColor='rgba(201,160,66,0.2)'">
                                    <div class="contact-icon-box" style="width: 40px; height: 40px; border: 1px solid rgba(201,160,66,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: border-color 0.2s;">
                                        @if($detail['icon'] === 'map')<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                        @elseif($detail['icon'] === 'phone')<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        @elseif($detail['icon'] === 'whatsapp')<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                        @elseif($detail['icon'] === 'mail')<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        @else<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>@endif
                                    </div>
                                    <div>
                                        <div style="font-size: 10px; color: rgba(201,160,66,0.55); letter-spacing: 0.25em; text-transform: uppercase; font-family: Outfit, sans-serif; margin-bottom: 4px;">{{ $detail['label'] }}</div>
                                        <div style="font-size: 13px; color: rgba(250,246,239,0.72); font-family: Outfit, sans-serif; line-height: 1.7; white-space: pre-line;">{{ $detail['value'] }}</div>
                                    </div>
                                </a>
                            @else
                                <div style="display: flex; gap: 16px; align-items: flex-start;">
                                    <div class="contact-icon-box" style="width: 40px; height: 40px; border: 1px solid rgba(201,160,66,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        @if($detail['icon'] === 'map')<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                        @elseif($detail['icon'] === 'phone')<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        @elseif($detail['icon'] === 'whatsapp')<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                        @elseif($detail['icon'] === 'mail')<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        @else<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>@endif
                                    </div>
                                    <div>
                                        <div style="font-size: 10px; color: rgba(201,160,66,0.55); letter-spacing: 0.25em; text-transform: uppercase; font-family: Outfit, sans-serif; margin-bottom: 4px;">{{ $detail['label'] }}</div>
                                        <div style="font-size: 13px; color: rgba(250,246,239,0.72); font-family: Outfit, sans-serif; line-height: 1.7; white-space: pre-line;">{{ $detail['value'] }}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div>
                    @include('partials.booking-form')
                </div>
            </div>

            @php
                $googleReviewsUrl = 'https://www.google.com/maps/place/Elite+Physio+Clinics/@52.2369026,-0.901841/data=!4m8!3m7!1s0x48770f24e9384521:0x63ef053434b83dcd!8m2!3d52.2369026!4d-0.901841!9m1!1b1';
                $googleWriteReviewUrl = 'https://www.google.com/search?q=Elite+Physio+Clinics#lrd=0x48770f24e9384521:0x63ef053434b83dcd,3,,,,';
                $googleReviews = [
                    [
                        'name' => 'Shain Ali',
                        'initial' => 'S',
                        'text' => 'Wafaa was excellent, welcoming, and very professional. I had 9/10 sessions with Wafaa and I can\'t recommend her enough! Every session was a step closer to my recovery.',
                    ],
                    [
                        'name' => 'Shannon Bradshaw',
                        'initial' => 'S',
                        'text' => 'I highly recommend her and appreciate the knowledge and skills she has to offer.',
                    ],
                    [
                        'name' => 'Colin Maxwell',
                        'initial' => 'C',
                        'text' => 'She was very thorough and attentive and the conversation flowed.',
                    ],
                ];
            @endphp

            <div id="reviews" class="map-reviews-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-top: 64px; align-items: stretch;">
                <a href="https://maps.app.goo.gl/WhAb8a7Bya6Tz5K38" target="_blank" rel="noopener noreferrer" class="contact-map" style="display: block; position: relative; border-radius: 3px; overflow: hidden; border: 1px solid rgba(201,160,66,0.15); min-height: 320px; cursor: pointer;">
                    <iframe title="Elite Physio Clinics Location" src="https://maps.google.com/maps?q=Mare+Fair,+Sol+Central+Ground+Floor,+Unit+3+Northampton+NN1+1SR&t=&z=16&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border: 0; filter: invert(90%) hue-rotate(180deg); display: block; pointer-events: none; position: absolute; inset: 0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 16px 12px 8px; background: linear-gradient(to top, rgba(10,31,19,0.85), transparent); display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="rgba(201,160,66,0.8)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                        <span style="font-size: 10px; color: rgba(201,160,66,0.8); letter-spacing: 0.2em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 500;">Open in Maps</span>
                    </div>
                    <div style="position: absolute; top: -1px; right: -1px; width: 28px; height: 28px; border-top: 2px solid rgba(201,160,66,0.4); border-right: 2px solid rgba(201,160,66,0.4); pointer-events: none;"></div>
                    <div style="position: absolute; bottom: -1px; left: -1px; width: 28px; height: 28px; border-bottom: 2px solid rgba(201,160,66,0.4); border-left: 2px solid rgba(201,160,66,0.4); pointer-events: none;"></div>
                </a>

                <div class="google-reviews" style="border: 1px solid rgba(201,160,66,0.15); border-radius: 3px; padding: 28px 24px; display: flex; flex-direction: column; background: rgba(250,246,239,0.02); position: relative;">
                    <div style="position: absolute; top: -1px; right: -1px; width: 28px; height: 28px; border-top: 2px solid rgba(201,160,66,0.4); border-right: 2px solid rgba(201,160,66,0.4); pointer-events: none;"></div>
                    <div style="position: absolute; bottom: -1px; left: -1px; width: 28px; height: 28px; border-bottom: 2px solid rgba(201,160,66,0.4); border-left: 2px solid rgba(201,160,66,0.4); pointer-events: none;"></div>

                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 20px;">
                        <div>
                            <div style="font-size: 10px; color: rgba(201,160,66,0.55); letter-spacing: 0.25em; text-transform: uppercase; font-family: Outfit, sans-serif; margin-bottom: 8px;">Google</div>
                            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 300; color: #faf6ef; margin: 0; line-height: 1;">Reviews</h3>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-family: 'Cormorant Garamond', serif; font-size: 36px; color: #c9a042; line-height: 1; font-weight: 400;">5.0</div>
                            <div style="display: flex; gap: 2px; justify-content: flex-end; margin: 6px 0 4px;" aria-label="5 out of 5 stars">
                                @for($i = 0; $i < 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#c9a042"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @endfor
                            </div>
                            <a href="{{ $googleReviewsUrl }}" target="_blank" rel="noopener noreferrer" style="font-size: 11px; color: rgba(250,246,239,0.45); font-family: Outfit, sans-serif; text-decoration: none; letter-spacing: 0.04em;" onmouseenter="this.style.color='#c9a042'" onmouseleave="this.style.color='rgba(250,246,239,0.45)'">53 Google reviews</a>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 16px; flex: 1; overflow: auto;">
                        @foreach($googleReviews as $index => $review)
                            <div style="padding-bottom: 16px;{{ $index < count($googleReviews) - 1 ? ' border-bottom: 1px solid rgba(201,160,66,0.1);' : '' }}">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                    <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(201,160,66,0.15); border: 1px solid rgba(201,160,66,0.25); display: flex; align-items: center; justify-content: center; font-size: 11px; color: #c9a042; font-family: Outfit, sans-serif; font-weight: 600; flex-shrink: 0;">{{ $review['initial'] }}</div>
                                    <div style="min-width: 0;">
                                        <div style="font-size: 12px; color: rgba(250,246,239,0.85); font-family: Outfit, sans-serif; font-weight: 500;">{{ $review['name'] }}</div>
                                        <div style="display: flex; gap: 1px; margin-top: 2px;">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="#c9a042"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p style="margin: 0; font-size: 13px; color: rgba(250,246,239,0.55); font-family: Outfit, sans-serif; font-weight: 300; line-height: 1.65;">“{{ $review['text'] }}”</p>
                            </div>
                        @endforeach
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 20px;">
                        <a href="{{ $googleWriteReviewUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 8px; background: #c9a042; color: #0a1f13; padding: 12px 20px; border-radius: 2px; text-decoration: none; font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 700;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            Add your review
                        </a>
                        <a href="{{ $googleReviewsUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; padding: 12px 18px; border: 1px solid rgba(201,160,66,0.3); color: rgba(250,246,239,0.7); border-radius: 2px; text-decoration: none; font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; font-family: Outfit, sans-serif; font-weight: 500;" onmouseenter="this.style.borderColor='rgba(201,160,66,0.6)'; this.style.color='#c9a042'" onmouseleave="this.style.borderColor='rgba(201,160,66,0.3)'; this.style.color='rgba(250,246,239,0.7)'">
                            View all
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer style="background: #070d0e; border-top: 1px solid rgba(201,160,66,0.1); padding: 40px 48px;">
        <div class="footer-inner" style="max-width: 1240px; margin: 0 auto; display: flex; flex-direction: row; justify-content: space-between; align-items: center; gap: 0; text-align: left;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('logo.png') }}" alt="Elite Physio Clinics" style="width: 28px; height: 28px; opacity: 0.7; border-radius: 50%; box-shadow: 0 0 0 1.5px rgba(201,160,66,0.3), 0 0 8px rgba(201,160,66,0.1);" />
                <span style="font-family: 'Cormorant Garamond', serif; font-size: 13px; color: rgba(250,246,239,0.35); letter-spacing: 0.12em;">ELITE PHYSIO CLINICS</span>
            </div>
            <div style="font-size: 11px; color: rgba(250,246,239,0.18); font-family: Outfit, sans-serif; letter-spacing: 0.08em;">
                © {{ date('Y') }} Elite Physio Clinics · Northampton, UK
            </div>
            <div class="footer-links" style="display: flex; gap: 28px; flex-wrap: wrap; justify-content: center;">
                @foreach($navLinks as $l)
                    <a href="#{{ strtolower($l) }}" style="font-size: 11px; color: rgba(250,246,239,0.28); text-decoration: none; letter-spacing: 0.14em; text-transform: uppercase; font-family: Outfit, sans-serif; transition: color 0.3s;" onmouseenter="this.style.color='rgba(201,160,66,0.7)'" onmouseleave="this.style.color='rgba(250,246,239,0.28)'">{{ $l }}</a>
                @endforeach
                <a href="{{ route('privacy') }}" target="_blank" rel="noopener noreferrer" style="font-size: 11px; color: rgba(250,246,239,0.28); text-decoration: none; letter-spacing: 0.14em; text-transform: uppercase; font-family: Outfit, sans-serif; transition: color 0.3s;" onmouseenter="this.style.color='rgba(201,160,66,0.7)'" onmouseleave="this.style.color='rgba(250,246,239,0.28)'">Privacy Policy</a>
            </div>
        </div>
    </footer>

    {{-- WhatsApp --}}
    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" title="Chat on WhatsApp" class="whatsapp-float" style="position: fixed; bottom: 24px; right: 24px; width: 56px; height: 56px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(37,211,102,0.4); z-index: 9999; cursor: pointer; text-decoration: none;">
        <svg viewBox="0 0 24 24" width="28" height="28" fill="white">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
    </a>
</div>

<style>
@media (max-width: 767px) {
    #main-nav { padding: 14px 20px !important; }
    .hide-mobile { display: none !important; }
    .show-mobile-only { display: flex !important; }
    .nav-logo-title { font-size: 13px !important; }
    .hero-eyebrow { font-size: 10px !important; letter-spacing: 0.2em !important; margin-bottom: 18px !important; }
    .hero-letter { font-size: clamp(44px, 14vw, 68px) !important; letter-spacing: 0.08em !important; }
    .hero-divider { width: 160px !important; margin: 16px 0 !important; }
    .hero-subtitle { font-size: 13px !important; letter-spacing: 0.3em !important; margin-bottom: 20px !important; }
    .hero-ctas { flex-direction: column !important; width: 100% !important; max-width: 300px !important; gap: 12px !important; }
    .hero-ctas a { width: 100% !important; padding: 13px 0 !important; }
    .stats-grid { grid-template-columns: 1fr !important; }
    .stat-cell { border-right: none !important; border-bottom: 1px solid rgba(201,160,66,0.1) !important; }
    .stat-cell:last-child { border-bottom: none !important; }
    .stat-value { font-size: 64px !important; }
    #services { padding: 64px 20px !important; }
    .services-header { flex-direction: column !important; align-items: flex-start !important; gap: 20px !important; margin-bottom: 36px !important; }
    .services-header h2 { font-size: 48px !important; }
    .services-intro { max-width: 100% !important; }
    .services-grid { grid-template-columns: 1fr !important; }
    .service-card { padding: 24px 20px !important; }
    .service-card h3 { font-size: 18px !important; }
    #tab-adult, #tab-paediatric { padding: 14px 12px !important; }
    .philosophy-inner { flex-direction: column !important; gap: 28px !important; text-align: center !important; padding: 60px 20px !important; }
    .philosophy-logo { width: 52px !important; height: 52px !important; }
    .philosophy-inner blockquote { font-size: 20px !important; }
    #about { padding: 64px 20px !important; }
    .about-grid { grid-template-columns: 1fr !important; gap: 48px !important; }
    .about-grid > div:first-child { max-width: 320px !important; margin: 0 auto !important; }
    .about-badge { bottom: -16px !important; right: -10px !important; }
    .about-badge div:first-child { font-size: 28px !important; }
    .about-grid > div:last-child { padding-top: 24px !important; }
    .gallery-fade-left, .gallery-fade-right { width: 40px !important; }
    .gallery-item { width: 220px !important; height: 160px !important; }
    .why-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 3px !important; }
    .why-grid > div { padding: 28px 20px !important; }
    .why-grid h3 { font-size: 17px !important; }
    #insurance { padding: 60px 20px !important; }
    .insurance-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
    .insurance-card { padding: 20px 16px !important; min-height: 70px !important; }
    .insurance-logo { max-width: 100px !important; max-height: 36px !important; }
    #contact { padding: 64px 20px !important; }
    .contact-header { margin-bottom: 40px !important; }
    .contact-header h2 { font-size: 44px !important; }
    .contact-grid { grid-template-columns: 1fr !important; gap: 48px !important; }
    .map-reviews-grid { grid-template-columns: 1fr !important; gap: 24px !important; margin-top: 40px !important; }
    .contact-map { min-height: 240px !important; }
    .google-reviews { padding: 22px 18px !important; }
    footer { padding: 32px 20px !important; }
    .footer-inner { flex-direction: column !important; gap: 16px !important; text-align: center !important; }
    .footer-links { gap: 20px !important; }
}
@media (min-width: 768px) and (max-width: 1023px) {
    .services-grid { grid-template-columns: repeat(2, 1fr) !important; }
    .about-grid { grid-template-columns: 1fr !important; }
    .why-grid { grid-template-columns: repeat(2, 1fr) !important; }
    .contact-grid { grid-template-columns: 1fr !important; }
    .map-reviews-grid { grid-template-columns: 1fr !important; }
    .hero-letter { font-size: clamp(68px, 10vw, 96px) !important; }
    .hero-subtitle { font-size: 18px !important; }
    .hero-tagline { font-size: 13px !important; }
}
</style>
@endsection
