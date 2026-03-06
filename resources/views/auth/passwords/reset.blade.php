<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | NEXORA_</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/nexor.png') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');

        :root {
            --nexora-green: #00FF66;
            --nexora-dark: #07120B;
            --nexora-card: #111A13;
            --nexora-border: #1A2E20;
        }

        body {
            background-color: var(--nexora-dark);
            color: white;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(circle at center, rgba(0, 255, 102, 0.05) 0%, transparent 60%);
        }

        .reset-container {
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .card {
            background-color: var(--nexora-card);
            border: 1px solid var(--nexora-border);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            overflow: hidden;
        }

        .card-body {
            padding: 40px;
        }

        .form-label {
            color: #8E9A92;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .form-control {
            background-color: rgba(255,255,255,0.03);
            border: 1px solid var(--nexora-border);
            color: white;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 1rem;
        }

        .form-control:focus {
            background-color: rgba(255,255,255,0.05);
            border-color: var(--nexora-green);
            box-shadow: 0 0 0 0.25rem rgba(0, 255, 102, 0.15);
            color: white;
        }

        .input-group-text {
            background-color: rgba(255,255,255,0.03);
            border: 1px solid var(--nexora-border);
            border-right: none;
            color: #8E9A92;
            border-radius: 12px 0 0 12px;
        }

        .form-control.with-icon {
            border-left: none;
            padding-left: 0;
        }

        .btn-primary {
            background-color: var(--nexora-green);
            border: none;
            color: var(--nexora-dark);
            font-weight: 700;
            padding: 14px;
            border-radius: 12px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: #00E65C;
            color: var(--nexora-dark);
            box-shadow: 0 0 20px rgba(0, 255, 102, 0.4);
            transform: translateY(-2px);
        }

        .invalid-feedback {
            color: #ff4d4d;
            font-size: 0.8rem;
            margin-top: 8px;
        }

        .form-control.is-invalid {
            border-color: #ff4d4d;
            background-image: none; /* remove bootstrap default icon */
        }
    </style>
</head>
<body>

<div class="reset-container">
    <div class="brand-header">
        <a href="{{ url('/') }}" style="text-decoration: none;">
            <img src="{{ asset('images/nexor.png') }}" alt="Nexora Logo" style="max-height: 80px; object-fit: contain; margin-bottom: 20px; filter: drop-shadow(0 4px 12px rgba(0, 255, 102, 0.2));">
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="text-white fw-bold mb-2">Create New Password</h4>
            <p class="text-secondary mb-4" style="font-size: 0.9rem;">
                Your new password must be at least 8 characters long.
            </p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input id="email" type="email" class="form-control with-icon @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly>
                    </div>
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password" class="form-control with-icon @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" autofocus placeholder="••••••••">
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                        <input id="password-confirm" type="password" class="form-control with-icon" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
