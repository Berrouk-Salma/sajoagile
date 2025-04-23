<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Agile App') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        html, body {
            height: 100%;
        }
        
        body {
            display: flex;
            flex-direction: column;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #4a00e0, #8e2de2);
            color: white;
            padding: 100px 0;
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #4a00e0;
            margin-bottom: 1rem;
        }
        
        .feature-card {
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 30px 0;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Agile App
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
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Welcome to Agile App</h1>
            <p class="lead mb-5">A powerful project management tool for agile teams</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg px-4 me-md-2">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 me-md-2">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">Register</a>
                    @endif
                @endauth
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Features</h2>
                <p class="lead text-muted">Discover what makes our agile app stand out</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <h3 class="card-title fw-bold">Project Management</h3>
                            <p class="card-text">Create and manage multiple projects with ease. Assign team members, track progress, and meet deadlines.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-running"></i>
                            </div>
                            <h3 class="card-title fw-bold">Sprint Planning</h3>
                            <p class="card-text">Plan your sprints, set goals, and allocate tasks. Keep your team on track with clear objectives.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <h3 class="card-title fw-bold">Task Management</h3>
                            <p class="card-text">Create, assign, and track tasks with our intuitive kanban board. Move tasks between different stages.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="card-title fw-bold">Team Collaboration</h3>
                            <p class="card-text">Work together with your team members. Share updates, leave comments, and stay in sync.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="card-title fw-bold">Progress Tracking</h3>
                            <p class="card-text">Monitor your project's progress with visual indicators and statistics. Stay on top of your goals.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h3 class="card-title fw-bold">Notifications</h3>
                            <p class="card-text">Stay informed with real-time notifications about task updates, comments, and team activities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="fw-bold mb-4">Ready to get started?</h2>
                    <p class="lead mb-4">Join thousands of teams using our platform to manage their projects more efficiently.</p>
                    <div class="d-grid gap-2 d-md-flex">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-4">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">Sign Up for Free</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-4">Log In</a>
                        @endauth
                    </div>
                </div>
                <div class="col-md-6 text-center mt-4 mt-md-0">
                    <i class="fas fa-laptop-code" style="font-size: 150px; color: #4a00e0;"></i>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Agile App</h5>
                    <p>A powerful project management tool for agile teams.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Agile App. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
