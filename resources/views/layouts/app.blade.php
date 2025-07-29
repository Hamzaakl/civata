<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Civata - Ev Hizmetleri Platformu')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        
        .service-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .category-card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .category-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .rating-stars {
            color: #ffc107;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0 20px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('home') }}">
                <i class="fas fa-tools"></i> Civata
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services.index') }}">Hizmetler</a>
                    </li>
                    
                                                @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('bookings.index') }}">
                                    @if(Auth::user()->isServiceProvider())
                                        Gelen Rezervasyonlar
                                    @else
                                        Rezervasyonlarım
                                    @endif
                                </a>
                            </li>
                            @if(Auth::user()->isServiceProvider())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('provider.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>
                                        Hizmet Sağlayıcı Paneli
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-cog me-1"></i>
                                        Admin Paneli
                                    </a>
                                </li>
                            @endif
                            @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Giriş Yap</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary text-white ms-2" href="{{ route('register') }}">Kayıt Ol</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                         style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-white" style="font-size: 14px;"></i>
                                    </div>
                                @endif
                                {{ Auth::user()->name }}
                                @if(Auth::user()->is_verified)
                                    <i class="fas fa-check-circle text-success ms-1" style="font-size: 12px;"></i>
                                @endif
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-2"></i> Profilim
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-edit me-2"></i> Profil Düzenle
                                </a></li>
                                @if(Auth::user()->isServiceProvider())
                                    <li><a class="dropdown-item" href="{{ route('provider.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Hizmet Sağlayıcı Paneli
                                    </a></li>
                                @endif
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-cog me-2"></i> Admin Paneli
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('bookings.index') }}">
                                    <i class="fas fa-calendar-check me-2"></i> 
                                    @if(Auth::user()->isServiceProvider())
                                        Gelen Rezervasyonlar
                                    @else
                                        Rezervasyonlarım
                                    @endif
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('reviews.index') }}">
                                    <i class="fas fa-star me-2"></i> 
                                    @if(Auth::user()->isServiceProvider())
                                        Aldığım Değerlendirmeler
                                    @else
                                        Değerlendirmelerim
                                    @endif
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Çıkış Yap
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h5><i class="fas fa-tools"></i> Civata</h5>
                    <p>Ev hizmetleri için güvenilir platform. En iyi hizmet sağlayıcıları ile buluşun.</p>
                </div>
                <div class="col-lg-4">
                    <h6>Hızlı Bağlantılar</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-light">Ana Sayfa</a></li>
                        <li><a href="{{ route('services.index') }}" class="text-light">Hizmetler</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6>İletişim</h6>
                    <p><i class="fas fa-envelope"></i> info@civata.com</p>
                    <p><i class="fas fa-phone"></i> 0555 123 4567</p>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; 2024 Civata. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
