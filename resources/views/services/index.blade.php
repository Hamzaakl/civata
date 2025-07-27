@extends('layouts.app')

@section('title', 'Hizmetler - Civata')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Filtreler -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Filtreler</h5>
                </div>
                <div class="card-body">
                    <form method="GET">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="">Tüm Kategoriler</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Şehir</label>
                            <input type="text" name="city" class="form-control" 
                                placeholder="Şehir..." value="{{ request('city') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Arama</label>
                            <input type="text" name="search" class="form-control" 
                                placeholder="Hizmet ara..." value="{{ request('search') }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filtrele
                        </button>
                        
                        @if(request()->hasAny(['category', 'city', 'search']))
                            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="fas fa-times"></i> Temizle
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Hizmet Listesi -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Hizmetler</h2>
                <div class="text-muted">
                    {{ $services->total() }} hizmet bulundu
                </div>
            </div>
            
            @if($services->count() > 0)
                <div class="row g-4">
                    @foreach($services as $service)
                        <div class="col-lg-6">
                            <div class="card service-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-primary">{{ $service->serviceCategory->name }}</span>
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
                                        {{ Str::limit($service->description, 120) }}
                                    </p>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Fiyat</small>
                                            <div class="text-primary fw-bold">{{ $service->price_range }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Konum</small>
                                            <div>
                                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                {{ $service->user->city }}
                                            </div>
                                        </div>
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
                                        
                                        <div>
                                            <a href="{{ route('services.show', $service->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                Detaylar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Sayfalama -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $services->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>Hizmet Bulunamadı</h4>
                    <p class="text-muted">Arama kriterlerinize uygun hizmet bulunamadı. Lütfen filtrelerinizi değiştirin.</p>
                    <a href="{{ route('services.index') }}" class="btn btn-primary">
                        Tüm Hizmetleri Görüntüle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 