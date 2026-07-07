<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />
    <title>Admin Portal | Elite Physio Clinics</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>
<body>
    <div class="login-page">
        <div class="login-bg-pattern"></div>
        <div class="login-bg-glow"></div>

        <div class="login-container">
            <div class="login-brand">
                <img src="{{ asset('logo.png') }}" alt="Elite Physio Clinics" class="login-logo-img" />
                <h1 class="login-title">Elite Physio Clinics</h1>
                <div class="login-divider"></div>
                <p class="login-subtitle">Administration Portal</p>
            </div>

            <div class="login-card">
                <form method="POST" action="{{ route('admin.login') }}" class="login-form">
                    @csrf
                    <div class="login-field">
                        <label for="email">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            Email Address
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="admin@elitephysioclinics.co.uk" />
                    </div>

                    <div class="login-field">
                        <label for="password">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Password
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                    </div>

                    @if($errors->any())
                        <div class="login-error">
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <button type="submit" class="login-submit">
                        Sign In
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </form>
            </div>

            <p class="login-footer">Secure access for authorised personnel only</p>
        </div>
    </div>
</body>
</html>
