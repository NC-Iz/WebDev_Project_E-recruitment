<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Recruitment') }} - Find Your Dream Job</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, #2557a7 0%, #164081 100%) !important;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 1.8rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white !important;
        }

        .btn-login,
        .btn-register {
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login {
            background-color: white;
            color: #2557a7;
            border: 2px solid white;
        }

        .btn-login:hover {
            background-color: transparent;
            color: white;
        }

        .btn-register {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            margin-left: 0.5rem;
        }

        .btn-register:hover {
            background-color: white;
            color: #2557a7;
        }

        .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
            border: none;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #2557a7;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        main {
            min-height: calc(100vh - 76px);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            color: #2557a7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }

            .btn-login,
            .btn-register {
                width: 100%;
                margin: 0.25rem 0;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="bi bi-briefcase-fill"></i>
                    E-Recruitment
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                        @if(Auth::user()->role === 'employer')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('employer.jobs.create') }}">
                                <i class="bi bi-plus-circle"></i> Post Job
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('employer.jobs.index') }}">
                                <i class="bi bi-briefcase"></i> My Jobs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('employer.applications.index') }}">
                                <i class="bi bi-inbox"></i> Applications
                            </a>
                        </li>
                        @elseif(Auth::user()->role === 'jobseeker')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('jobseeker.jobs.index') }}">
                                <i class="bi bi-search"></i> Browse Jobs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('jobseeker.saved.index') }}">
                                <i class="bi bi-bookmark"></i> Saved Jobs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('jobseeker.applications.index') }}">
                                <i class="bi bi-file-earmark-text"></i> My Applications
                            </a>
                        </li>
                        @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="btn btn-login" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="btn btn-register" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                @if(isset($userProfile) && $userProfile && $userProfile->profile_picture)
                                <img src="{{ asset('uploads/profiles/' . $userProfile->profile_picture) }}"
                                    alt="Profile"
                                    style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid white;">
                                @else
                                <span class="user-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->role === 'jobseeker')
                                <a class="dropdown-item" href="{{ route('jobseeker.profile.show') }}">
                                    <i class="bi bi-person-circle"></i> My Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                @endif

                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>