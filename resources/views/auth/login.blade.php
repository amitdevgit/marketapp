<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - MarketApp</title>
    
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
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        
        .login-header {
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
        
        .login-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .login-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .login-form {
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
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }
        
        .remember-me label {
            font-size: 14px;
            color: #6b7280;
        }
        
        .forgot-link {
            font-size: 14px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            color: #5a67d8;
        }
        
        .login-btn {
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
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .register-link {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
        }
        
        .register-link p {
            font-size: 14px;
            color: #6b7280;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
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
        
        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .login-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to your MarketApp account</p>
        </div>
        
        <div class="login-form">
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            
            @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
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
                        autofocus 
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
                        placeholder="Enter your password"
                        required 
                        autocomplete="current-password"
                    >
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Remember me</label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                    @endif
                </div>
                
                <button type="submit" class="login-btn">
                    Sign In
                </button>
            </form>
            
            <div class="register-link">
                <p>Don't have an account? <a href="{{ route('register') }}">Sign up here</a></p>
            </div>
        </div>
    </div>
</body>
</html>