<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Nexora</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #2e7d32;
            --primary-hover: #1b5e20;
            --glass-bg: rgba(20, 40, 20, 0.4);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-color: #ffffff;
            --error-color: #ff5252;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body, html {
            height: 100%;
            width: 100%;
            overflow: hidden;
            background-color: #0b1a0b;
        }

        /* Animated Forest Background */
        .background-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1542273917363-3b1817f69a5d?q=80&w=2074&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            /* Subtle zoom animation */
            animation: zoomBg 30s infinite alternate linear;
            z-index: -2;
        }
        
        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Dark gradient overlay for readability and mood */
            background: linear-gradient(135deg, rgba(5, 15, 5, 0.8) 0%, rgba(10, 30, 10, 0.6) 100%);
            z-index: -1;
        }

        /* Particles effect representation */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveParticles 20s infinite linear;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes zoomBg {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        @keyframes moveParticles {
            0% { transform: translateY(0); }
            100% { transform: translateY(-50px); }
        }

        /* Login Container Animations */
        @keyframes fadeSlideUp {
            0% { 
                opacity: 0; 
                transform: translateY(40px);
            }
            100% { 
                opacity: 1; 
                transform: translateY(0);
            }
        }

        @keyframes floatLogo {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @keyframes glow {
            0% { box-shadow: 0 0 5px rgba(46, 125, 50, 0.2); }
            50% { box-shadow: 0 0 20px rgba(46, 125, 50, 0.6); }
            100% { box-shadow: 0 0 5px rgba(46, 125, 50, 0.2); }
        }

        /* Main Layout */
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Glassmorphism Card */
        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.5);
            animation: fadeSlideUp 0.8s ease-out forwards;
            color: var(--text-color);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            animation: floatLogo 4s ease-in-out infinite;
        }

        .logo-container img {
            max-width: 140px;
            height: auto;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
        }
        
        .login-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 1px;
        }

        /* Form Inputs */
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.6);
            transition: color 0.3s;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-control:focus {
            background: rgba(0, 0, 0, 0.4);
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(46, 125, 50, 0.3);
        }

        .form-control:focus + i, .form-control:not(:placeholder-shown) + i {
            color: var(--primary-color);
        }

        /* Interactive Error States */
        .is-invalid {
            border-color: var(--error-color) !important;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .invalid-feedback {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 8px;
            display: block;
            padding-left: 5px;
        }

        /* Checkbox */
        .options-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .form-check-input {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-radius: 4px;
            margin-right: 8px;
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
            background: transparent;
        }

        .form-check-input:checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:checked::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            color: white;
            font-size: 12px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .forgot-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: white;
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            animation: glow 3s infinite;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(46, 125, 50, 0.4);
            background: linear-gradient(135deg, #388e3c, #2e7d32);
        }
        
        .btn-login:active {
            transform: translateY(1px);
        }

    </style>
</head>
<body>

    <div class="background-container"></div>
    <div class="background-overlay"></div>
    <div class="particles"></div>

    <div class="login-wrapper">
        <div class="login-card">
            
            <div class="logo-container">
                <img src="{{ asset('images/nexor.png') }}" alt="Nexora Logo">
            </div>
            
            <h2 class="login-title">Admin Portal</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('Email Address') }}" required autocomplete="email" autofocus>
                    <i class="fa-solid fa-envelope"></i>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
                    <i class="fa-solid fa-lock"></i>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="options-group">
                    <label class="form-check" for="remember">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="form-check-label">{{ __('Remember Me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    {{ __('Sign In') }}
                </button>
            </form>
        </div>
    </div>

</body>
</html>
