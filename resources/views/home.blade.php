@extends('layouts.app')

@section('title', 'Civata - Ev Hizmetleri Platformu')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Evinizdeki Her İş İçin<br>
                    <span class="text-warning">Güvenilir Uzmanlar</span>
                </h1>
                <p class="lead mb-4">
                    Kamera montajından tesisat tamire, temizlikten teknik servise kadar 
                    tüm ev hizmetleri için doğru adresi buldunuz.
                </p>
                
                <!-- Arama Formu -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('search') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <select name="category" class="form-select">
                                        <option value="">Kategori Seçin</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="city" class="form-control" placeholder="Şehir...">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Ara
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-home" style="font-size: 15rem; opacity: 0.1;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Kategoriler -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Hizmet Kategorileri</h2>
            <p class="text-muted">İhtiyacınız olan hizmeti kolayca bulun</p>
        </div>
        
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas {{ $category->icon }}"></i>
                            </div>
                            <h5 class="fw-bold text-dark">{{ $category->name }}</h5>
                            <p class="text-muted small">{{ $category->description }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Öne Çıkan Hizmetler -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Öne Çıkan Hizmetler</h2>
            <p class="text-muted">En çok tercih edilen hizmetler</p>
        </div>
        
        <div class="row g-4">
            @foreach($featuredServices as $service)
                <div class="col-lg-4 col-md-6">
                    <div class="card service-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-primary">{{ $service->serviceCategory->name }}</span>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $service->user->rating ? '' : '-o' }}"></i>
                                    @endfor
                                    <small class="text-muted ms-1">({{ $service->user->total_reviews }})</small>
                                </div>
                            </div>
                            
                            <h5 class="card-title">{{ $service->title }}</h5>
                            <p class="card-text text-muted">
                                {{ Str::limit($service->description, 100) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="text-primary fw-bold">{{ $service->price_range }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $service->user->city }}
                                </small>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        {{ substr($service->user->name, 0, 1) }}
                                    </div>
                                    <small class="text-muted">{{ $service->user->name }}</small>
                                </div>
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-primary btn-sm">
                                    Detaylar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg">
                Tüm Hizmetleri Görüntüle
            </a>
        </div>
    </div>
</section>

<!-- En İyi Hizmet Sağlayıcılar -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">En İyi Hizmet Sağlayıcılar</h2>
            <p class="text-muted">Yüksek puan alan uzmanlarımız</p>
        </div>
        
        <div class="row g-4">
            @foreach($topProviders as $provider)
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.5rem;">
                                {{ substr($provider->name, 0, 1) }}
                            </div>
                            
                            <h5 class="card-title">{{ $provider->name }}</h5>
                            
                            <div class="rating-stars mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $provider->rating ? '' : '-o' }}"></i>
                                @endfor
                                <div class="small text-muted">{{ $provider->total_reviews }} değerlendirme</div>
                            </div>
                            
                            <p class="card-text small text-muted">
                                {{ Str::limit($provider->bio, 80) }}
                            </p>
                            
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt"></i> {{ $provider->city }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Nasıl Çalışır -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Nasıl Çalışır?</h2>
            <p class="text-muted">3 adımda hizmet alın</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 text-center">
                <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    1
                </div>
                <h5>Hizmet Ara</h5>
                <p class="text-muted">İhtiyacınız olan hizmeti arayın ve en uygun uzmanları bulun.</p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    2
                </div>
                <h5>Teklif Al</h5>
                <p class="text-muted">Uzmanlardan teklif alın ve size en uygun olanı seçin.</p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    3
                </div>
                <h5>Hizmeti Alın</h5>
                <p class="text-muted">İşinizi güvenle tamamlayın ve değerlendirmenizi yapın.</p>
            </div>
        </div>
    </div>
</section>
@endsection
