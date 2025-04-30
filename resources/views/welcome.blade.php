<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Agile App') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4e46e5;
            --light-bg: #f0f4ff;
            --neutral-100: #f9fafb;
            --neutral-200: #f3f4f6;
            --neutral-300: #e5e7eb;
            --text-dark: #333;
            --text-light: #6b7280;
        }
        
        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }
        
        body {
            display: flex;
            flex-direction: column;
            background-color: var(--neutral-100);
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 0.75rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.5rem;
            padding: 0;
        }
        
        .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
        }
        
        .hero-section {
            background-color: var(--neutral-100);
            padding: 80px 0 60px;
            border-bottom: 1px solid var(--neutral-300);
        }
        
        .hero-image {
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            height: auto;
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            font-weight: 400;
        }
        
        .features-section {
            padding: 60px 0;
            background-color: white;
        }
        
        .feature-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .feature-tabs {
            display: flex;
            border-bottom: 1px solid var(--neutral-300);
            margin-bottom: 2rem;
        }
        
        .feature-tab {
            padding: 1rem 1.5rem;
            margin-right: 0.5rem;
            font-weight: 500;
            color: var(--text-light);
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        
        .feature-tab.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
        }
        
        .feature-quote {
            background-color: var(--light-bg);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            border-radius: 6px;
            margin: 2rem 0;
        }
        
        .quote-text {
            font-style: italic;
            color: var(--text-dark);
            font-weight: 400;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        
        .quote-author {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .footer {
            background-color: white;
            border-top: 1px solid var(--neutral-300);
            padding: 50px 0 30px;
            margin-top: auto;
        }
        
        .footer-heading {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1.25rem;
            color: var(--text-dark);
        }
        
        .footer-link {
            display: block;
            color: var(--text-light);
            text-decoration: none;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }
        
        .footer-link:hover {
            color: var(--primary-color);
        }
        
        .social-icons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .social-icon {
            color: var(--text-light);
            font-size: 1.2rem;
        }
        
        .social-icon:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Sajo Agile 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Log in</a>
                            </li>

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <h1 class="hero-title">Move fast, stay aligned, and build better - together</h1>
                    <p class="hero-subtitle">The #1 software development tool used by agile teams</p>
                    <div class="d-grid gap-2 d-md-flex">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary me-md-2">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary me-md-2">Get started for free</a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-7">
                    <img src="{{ asset('storage/images/image.png') }}" alt="Agile App Interface" class="hero-image">

                </div>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="feature-title">All from a single source of truth</h2>
            </div>
            
            <div class="feature-tabs">
                <div class="feature-tab active">Plan</div>
                <div class="feature-tab">Track</div>
                <div class="feature-tab">Release</div>
                <div class="feature-tab">Report</div>
                <div class="feature-tab">Automate</div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h3>Plan</h3>
                    <p class="mb-4">Create your backlog, plan sprints, and distribute tasks across your software team. Prioritize and discuss your team's work in full context with complete visibility.</p>
                    
                    <div class="feature-quote">
                        <p class="quote-text">Work becomes a lot more simple when it's all in one place. It makes collaboration whole lot easier.</p>
                        <p class="quote-author">APEX AI</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('storage/images/images.jpg') }}" alt="Agile App Interface" class="hero-image">
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <h5 class="footer-heading">PRODUCTS</h5>
                    <a href="#" class="footer-link">Software</a>
                    <a href="#" class="footer-link">Project Management</a>
                    <a href="#" class="footer-link">Confluence</a>
                    <a href="#" class="footer-link">Trello</a>
                    <a href="#" class="footer-link">Bitbucket</a>
                    <a href="#" class="footer-link">View all products</a>
                </div>
                
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <h5 class="footer-heading">RESOURCES</h5>
                    <a href="#" class="footer-link">Technical Support</a>
                    <a href="#" class="footer-link">Purchasing & Licensing</a>
                    <a href="#" class="footer-link">Agile Community</a>
                    <a href="#" class="footer-link">Knowledge base</a>
                    <a href="#" class="footer-link">Marketplace</a>
                    <a href="#" class="footer-link">My Account</a>
                </div>
                
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <h5 class="footer-heading">EXPAND & LEARN</h5>
                    <a href="#" class="footer-link">Partners</a>
                    <a href="#" class="footer-link">Training & Certification</a>
                    <a href="#" class="footer-link">Documentation</a>
                    <a href="#" class="footer-link">Developer Resources</a>
                    <a href="#" class="footer-link">Enterprise services</a>
                    <a href="#" class="footer-link">View all resources</a>
                </div>
                
                <div class="col-6 col-md-3">
                    <h5 class="footer-heading">ABOUT AGILUX</h5>
                    <a href="#" class="footer-link">Company</a>
                    <a href="#" class="footer-link">Careers</a>
                    <a href="#" class="footer-link">Events</a>
                    <a href="#" class="footer-link">Blogs</a>
                    <a href="#" class="footer-link">Atlassian Foundation</a>
                    <a href="#" class="footer-link">Trust & Security</a>
                    <a href="#" class="footer-link">Contact us</a>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                <div>
                    <p class="text-muted">&copy; {{ date('Y') }} Agile. All rights reserved.</p>
                </div>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>