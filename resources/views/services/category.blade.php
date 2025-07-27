@extends('layouts.app')

@section('title', $category->name . ' - Civata')

@section('content')
<!-- Kategori Header -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Hizmetler</a></li>
                        <li class="breadcrumb-item active">{{ $category->name }}</li>
                    </ol>
                </nav>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="category-icon me-3">
                        <i class="fas {{ $category->icon }}"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1">{{ $category->name }}</h1>
                        <p class="text-muted mb-0">{{ $category->description }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="text-muted">
                    <strong>{{ $services->total() }}</strong> hizmet bulundu
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    @if($services->count() > 0)
        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-lg-4 col-md-6">
                    <div class="card service-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-primary">{{ $category->name }}</span>
                                @if($service->is_featured)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star"></i> Öne Çıkan
                                    </span>
                                @endif
                            </div>
                            
                            <h5 class="card-title">
                                <a href="{{ route('services.show', $service->id) }}" 
                                   class="text-decoration-none">
                                    {{ $service->title }}
                                </a>
                            </h5>
                            
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
                                    <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 32px; height: 32px;">
                                        {{ substr($service->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="small fw-bold">{{ $service->user->name }}</div>
                                        <div class="rating-stars small">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $service->user->rating ? '' : '-o' }}"></i>
                                            @endfor
                                            <span class="text-muted ms-1">({{ $service->user->total_reviews }})</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('services.show', $service->id) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    Detaylar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Sayfalama -->
        <div class="d-flex justify-content-center mt-5">
            {{ $services->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="category-icon text-muted mb-4" style="font-size: 5rem;">
                <i class="fas {{ $category->icon }}"></i>
            </div>
            <h4>Bu Kategoride Henüz Hizmet Yok</h4>
            <p class="text-muted">{{ $category->name }} kategorisinde henüz yayınlanmış hizmet bulunmuyor.</p>
            <a href="{{ route('services.index') }}" class="btn btn-primary">
                Tüm Hizmetleri Görüntüle
            </a>
        </div>
    @endif
</div>
@endsection 