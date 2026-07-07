@extends('layouts.app')

@section('title', 'Privacy Policy | Elite Physio Clinics')

@section('content')
<div style="background: #0a1f13; min-height: 100vh; color: #faf6ef; font-family: Outfit, sans-serif;">
    <nav style="padding: 24px 48px; border-bottom: 1px solid rgba(201,160,66,0.1);">
        <div style="max-width: 960px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: #c9a042;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                <span style="font-size: 13px; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 500;">Back to Home</span>
            </a>
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('logo.png') }}" alt="Elite Physio Clinics" style="width: 32px; height: 32px; opacity: 0.9; border-radius: 50%; box-shadow: 0 0 0 1.5px rgba(201,160,66,0.3);" />
            </div>
        </div>
    </nav>

    <main style="padding: 64px 24px; max-width: 860px; margin: 0 auto;">
        <div>
            <div style="font-size: 11px; color: #c9a042; letter-spacing: 0.35em; text-transform: uppercase; font-weight: 600; margin-bottom: 16px;">
                ── LEGAL INFORMATION
            </div>
            <h1 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(40px, 5vw, 64px); font-weight: 300; margin-bottom: 48px; line-height: 1.1;">
                Privacy <em style="color: #c9a042;">Policy</em>
            </h1>

            <div style="font-size: 15px; line-height: 1.8; color: rgba(250,246,239,0.85); display: flex; flex-direction: column; gap: 32px;">
                <p><strong>Last Updated:</strong> March 2026</p>

                <p>
                    At Elite Physio Clinics, we are committed to protecting and respecting your privacy. This policy explains when and why we collect personal information about people who visit our website or use our services, how we use it, the conditions under which we may disclose it to others, and how we keep it secure. This policy complies with the UK General Data Protection Regulation (UK GDPR) and the Data Protection Act 2018.
                </p>

                <section>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #c9a042; margin-bottom: 16px; font-weight: 500;">1. Who We Are</h2>
                    <p>We are Elite Physio Clinics. You can contact us regarding your data at:</p>
                    <ul style="list-style-type: disc; padding-left: 24px; margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                        <li><strong>Email:</strong> elitephysioclinics@gmail.com</li>
                        <li><strong>Phone:</strong> +44 333 577 9553</li>
                        <li><strong>Address:</strong> Mare Fair, Sol Central, Ground Floor, Unit 3, Northampton NN1 1SR</li>
                    </ul>
                </section>

                <section>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #c9a042; margin-bottom: 16px; font-weight: 500;">2. What Information We Collect</h2>
                    <p>We collect different types of information depending on how you interact with us:</p>
                    <ul style="list-style-type: disc; padding-left: 24px; margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                        <li><strong>Standard Personal Data:</strong> Name, email address, phone number, and billing address.</li>
                        <li><strong>Special Category Data (Health Data):</strong> Medical history, GP details, treatment records, and notes from your physiotherapy sessions.</li>
                        <li><strong>Technical Data:</strong> IP address, browser type, and information about how you use our website (via cookies).</li>
                    </ul>
                </section>

                <section>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #c9a042; margin-bottom: 16px; font-weight: 500;">3. How We Collect Your Information</h2>
                    <p>We collect information when you:</p>
                    <ul style="list-style-type: disc; padding-left: 24px; margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                        <li>Book an appointment online, over the phone, or in person.</li>
                        <li>Provide medical history forms before or during your consultation.</li>
                        <li>Interact with our website.</li>
                    </ul>
                </section>

                <section>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #c9a042; margin-bottom: 16px; font-weight: 500;">4. Why We Collect Your Data & Our Legal Basis</h2>
                    <p>Under UK GDPR, we must have a valid legal reason to process your data. We rely on the following:</p>
                    <ul style="list-style-type: disc; padding-left: 24px; margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                        <li><strong>Contract/Provision of Health Care:</strong> We need your health and contact data to assess your condition, provide physiotherapy treatments, and fulfill our contract with you.</li>
                        <li><strong>Legitimate Interests:</strong> To run our business smoothly, send appointment reminders, and improve our services.</li>
                        <li><strong>Legal Obligation:</strong> To keep financial and medical records for the time periods required by UK law.</li>
                    </ul>
                </section>

                <section>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #c9a042; margin-bottom: 16px; font-weight: 500;">5. Who We Share Your Information With</h2>
                    <p>We will never sell your personal data. We only share it when necessary to provide your care:</p>
                    <ul style="list-style-type: disc; padding-left: 24px; margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                        <li><strong>Medical Professionals:</strong> Your GP or other specialists (only with your explicit permission).</li>
                        <li><strong>Insurance Companies:</strong> If your treatment is covered by a health insurance provider.</li>
                        <li><strong>Service Providers:</strong> Companies that help us run our business safely, such as secure booking systems, payment processors, and our website hosting provider.</li>
                    </ul>
                </section>

                <section>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #c9a042; margin-bottom: 16px; font-weight: 500;">6. How We Store and Protect Your Data</h2>
                    <p>
                        We take data security seriously. All patient records and personal data are stored securely in encrypted, password-protected practice management software. We keep your personal data only for as long as necessary. By law, medical records in the UK must generally be retained for <strong>8 years</strong> after your last treatment (or until age 25 for treating children).
                    </p>
                </section>

                <section>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #c9a042; margin-bottom: 16px; font-weight: 500;">7. Your Rights</h2>
                    <p>Under UK data protection law, you have the right to:</p>
                    <ul style="list-style-type: disc; padding-left: 24px; margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                        <li><strong>Request access</strong> to the personal data we hold about you.</li>
                        <li><strong>Ask us to correct</strong> any inaccurate or incomplete information.</li>
                        <li><strong>Request deletion</strong> of your personal information (Note: this is limited for medical records due to legal retention requirements).</li>
                    </ul>
                    <p style="margin-top: 12px;">If you wish to exercise any of these rights, please contact us at <strong>elitephysioclinics@gmail.com</strong>.</p>
                </section>

                <section>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #c9a042; margin-bottom: 16px; font-weight: 500;">8. How to Complain</h2>
                    <p>If you have any concerns about our use of your personal information, you can make a complaint to us at <strong>elitephysioclinics@gmail.com</strong>.</p>
                    <p style="margin-top: 12px;">You can also complain to the Information Commissioner's Office (ICO) if you are unhappy with how we have used your data.</p>
                    <ul style="list-style-type: none; padding-left: 0; margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
                        <li><strong>ICO website:</strong> <a href="https://www.ico.org.uk" target="_blank" rel="noopener noreferrer" style="color: #c9a042; text-decoration: underline;">https://www.ico.org.uk</a></li>
                        <li><strong>ICO Helpline:</strong> 0303 123 1113</li>
                    </ul>
                </section>
            </div>
        </div>
    </main>
</div>
@endsection
