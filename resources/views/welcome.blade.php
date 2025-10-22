<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MarketApp - Professional Business Dashboard</title>
    
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
            flex-direction: column;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }
        
        .nav-link:hover {
            opacity: 0.8;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
        }
        
        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-outline:hover {
            background: white;
            color: #667eea;
        }
        
        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 20px 80px;
        }
        
        .hero-content {
            max-width: 800px;
        }
        
        .hero-title {
            font-size: 64px;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            line-height: 1.1;
        }
        
        .hero-subtitle {
            font-size: 24px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            line-height: 1.4;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .features {
            background: white;
            padding: 80px 20px;
        }
        
        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .features-title {
            text-align: center;
            font-size: 48px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 60px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }
        
        .feature-card {
            text-align: center;
            padding: 40px 20px;
            border-radius: 16px;
            background: #f8fafc;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        
        .feature-title {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }
        
        .feature-description {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.6;
        }
        
        .footer {
            background: #1f2937;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-text {
            font-size: 14px;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 48px;
            }
            
            .hero-subtitle {
                font-size: 20px;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .nav-links {
                gap: 20px;
            }
            
            .features-title {
                font-size: 36px;
            }
        }
        
        @media (max-width: 480px) {
            .hero-title {
                font-size: 36px;
            }
            
            .hero-subtitle {
                font-size: 18px;
            }
            
            .nav {
                flex-direction: column;
                gap: 20px;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="/" class="logo">
                <div class="logo-icon">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="logo-text">MarketApp</span>
            </a>
            
            <div class="nav-links">
                <a href="#features" class="nav-link">Features</a>
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
            </div>
        </nav>
    </header>
    
    <main class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Professional Business Dashboard</h1>
            <p class="hero-subtitle">Manage your business with our modern, intuitive dashboard. Track sales, manage inventory, and grow your business with powerful analytics.</p>
            
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary">Start Free Trial</a>
                <a href="#features" class="btn btn-outline">Learn More</a>
            </div>
        </div>
    </main>
    
    <section class="features" id="features">
        <div class="features-container">
            <h2 class="features-title">Everything You Need to Succeed</h2>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Analytics Dashboard</h3>
                    <p class="feature-description">Get real-time insights into your business performance with comprehensive analytics and reporting tools.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Product Management</h3>
                    <p class="feature-description">Easily manage your inventory, track products, and monitor stock levels with our intuitive product management system.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Customer Management</h3>
                    <p class="feature-description">Build strong relationships with your customers through our comprehensive customer management and communication tools.</p>
                </div>
            </div>
        </div>
    </section>
    
    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">© 2024 MarketApp. All rights reserved. Professional Business Management Dashboard.</p>
        </div>
    </footer>
</body>
</html>