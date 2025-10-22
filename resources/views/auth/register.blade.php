<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - MarketApp</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            position: relative;
        }
        
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .logo svg {
            width: 30px;
            height: 30px;
        }
        
        .register-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .register-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .register-form {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-input::placeholder {
            color: #9ca3af;
        }
        
        .password-strength {
            margin-top: 8px;
            font-size: 12px;
            color: #6b7280;
        }
        
        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 30px;
        }
        
        .terms-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
            margin-top: 2px;
        }
        
        .terms-checkbox label {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
        }
        
        .terms-checkbox a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .terms-checkbox a:hover {
            color: #5a67d8;
        }
        
        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .register-btn:active {
            transform: translateY(0);
        }
        
        .register-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .login-link {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
        }
        
        .login-link p {
            font-size: 14px;
            color: #6b7280;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            color: #5a67d8;
        }
        
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .password-match {
            margin-top: 8px;
            font-size: 12px;
        }
        
        .password-match.match {
            color: #16a34a;
        }
        
        .password-match.no-match {
            color: #dc2626;
        }
        
        @media (max-width: 480px) {
            .register-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .register-header {
                padding: 30px 20px;
            }
            
            .register-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="logo">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h1 class="register-title">Create Account</h1>
            <p class="register-subtitle">Join MarketApp and start managing your business</p>
        </div>
        
        <div class="register-form">
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        class="form-input" 
                        value="{{ old('name') }}" 
                        placeholder="Enter your full name"
                        required 
                        autofocus 
                        autocomplete="name"
                    >
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        class="form-input" 
                        value="{{ old('email') }}" 
                        placeholder="Enter your email"
                        required 
                        autocomplete="username"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Create a strong password"
                        required 
                        autocomplete="new-password"
                        minlength="8"
                    >
                    <div class="password-strength">Password must be at least 8 characters long</div>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        class="form-input" 
                        placeholder="Confirm your password"
                        required 
                        autocomplete="new-password"
                    >
                    <div class="password-match" id="passwordMatch"></div>
                </div>
                
                <div class="terms-checkbox">
                    <input id="terms" type="checkbox" required>
                    <label for="terms">
                        I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="register-btn" id="submitBtn">
                    Create Account
                </button>
            </form>
            
            <div class="login-link">
                <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
            </div>
        </div>
    </div>
    
    <script>
        // Password confirmation check
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('passwordMatch');
        const submitBtn = document.getElementById('submitBtn');
        
        function checkPasswordMatch() {
            if (confirmPassword.value === '') {
                passwordMatch.textContent = '';
                passwordMatch.className = 'password-match';
                return;
            }
            
            if (password.value === confirmPassword.value) {
                passwordMatch.textContent = '✓ Passwords match';
                passwordMatch.className = 'password-match match';
            } else {
                passwordMatch.textContent = '✗ Passwords do not match';
                passwordMatch.className = 'password-match no-match';
            }
        }
        
        confirmPassword.addEventListener('input', checkPasswordMatch);
        password.addEventListener('input', checkPasswordMatch);
        
        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.value.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
        });
    </script>
</body>
</html>