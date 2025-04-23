<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Agile App') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
        }
        
        .content {
            padding: 20px;
        }
        
        .task-card {
            cursor: pointer;
            margin-bottom: 10px;
        }
        
        .kanban-column {
            min-height: 300px;
        }
        
        .dropdown-menu {
            z-index: 1021;
        }
    </style>
</head>
<body class="bg-light">
    <div id="app">
        @include('layouts.navigation')

        <div class="container-fluid">
            <div class="row">
                @auth
                    <div class="col-md-2 bg-dark text-white sidebar pt-3 d-none d-md-block">
                        @include('layouts.sidebar')
                    </div>
                    <main class="col-md-10 ms-sm-auto content">
                        @include('layouts.flash-messages')
                        @yield('content')
                    </main>
                @else
                    <main class="col-12 content">
                        @include('layouts.flash-messages')
                        @yield('content')
                    </main>
                @endauth
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        
        // Enable dropdown menus
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
          return new bootstrap.Dropdown(dropdownToggleEl)
        })
    </script>
    
    @yield('scripts')
</body>
</html>
