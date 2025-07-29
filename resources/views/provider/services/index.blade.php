@extends('layouts.app')

@section('title', 'Hizmetlerim')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/provider-services.css') }}">
@endpush

@section('content')
<div class="provider-services-wrapper">
    <div class="container py-4">
        <!-- Başlık -->
        <div class="services-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="services-title">
                        <i class="fas fa-tools text-primary me-2"></i>
                        Hizmetlerim
                    </h2>
                    <p class="text-muted">Hizmetlerinizi yönetin ve düzenleyin</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('provider.services.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Yeni Hizmet Ekle
                    </a>
                </div>
            </div>
        </div>

        <!-- Hizmet Listesi -->
        @if($services->count() > 0)
            <div class="services-grid">
                @foreach($services as $service)
                    <div class="service-card">
                        <!-- Hizmet Görsel -->
                        <div class="service-image">
                            @if($service->images && count(json_decode($service->images)) > 0)
                                <img src="{{ asset('storage/' . json_decode($service->images)[0]) }}" 
                                     alt="{{ $service->title }}" class="img-fluid">
                            @else
                                <div class="no-image">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <!-- Durum Rozeti -->
                            <div class="status-badge status-{{ $service->is_active ? 'active' : 'inactive' }}">
                                @if($service->is_active)
                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                @else
                                    <i class="fas fa-pause-circle me-1"></i>Pasif
                                @endif
                            </div>

                            @if($service->is_featured)
                                <div class="featured-badge">
                                    <i class="fas fa-star me-1"></i>Öne Çıkan
                                </div>
                            @endif
                        </div>

                        <!-- Hizmet Bilgileri -->
                        <div class="service-content">
                            <div class="service-header">
                                <h5 class="service-title">{{ $service->title }}</h5>
                                <span class="service-category">{{ $service->serviceCategory->name }}</span>
                            </div>

                            <p class="service-description">
                                {{ Str::limit($service->description, 120) }}
                            </p>

                            <!-- Fiyat -->
                            @if($service->price_min && $service->price_max)
                                <div class="service-price">
                                    <i class="fas fa-lira-sign text-success me-1"></i>
                                    {{ number_format($service->price_min) }} - {{ number_format($service->price_max) }} TL
                                    <small class="text-muted">({{ ucfirst($service->price_type) }})</small>
                                </div>
                            @endif

                            <!-- İstatistikler -->
                            <div class="service-stats">
                                <div class="stat-item">
                                    <i class="fas fa-calendar-check text-primary me-1"></i>
                                    <span>{{ $service->bookings_count }} rezervasyon</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-eye text-info me-1"></i>
                                    <span>{{ $service->views }} görüntülenme</span>
                                </div>
                            </div>

                            <!-- Hizmet Alanı -->
                            <div class="service-area">
                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                <small class="text-muted">{{ $service->service_area }}</small>
                            </div>
                        </div>

                        <!-- Aksiyon Butonları -->
                        <div class="service-actions">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('services.show', $service) }}" 
                                   class="btn btn-outline-primary btn-sm"
                                   target="_blank">
                                    <i class="fas fa-eye me-1"></i>
                                    Görüntüle
                                </a>
                                <a href="{{ route('provider.services.edit', $service) }}" 
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-edit me-1"></i>
                                    Düzenle
                                </a>
                            </div>

                            <div class="mt-2">
                                <div class="btn-group w-100" role="group">
                                    <form method="POST" action="{{ route('provider.services.toggle-status', $service) }}" class="flex-fill">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-{{ $service->is_active ? 'warning' : 'success' }} btn-sm w-100">
                                            @if($service->is_active)
                                                <i class="fas fa-pause me-1"></i>Pasifleştir
                                            @else
                                                <i class="fas fa-play me-1"></i>Aktifleştir
                                            @endif
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('provider.services.destroy', $service) }}" 
                                          class="flex-fill ms-1"
                                          onsubmit="return confirm('Bu hizmeti silmek istediğinizden emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="fas fa-trash me-1"></i>Sil
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Oluşturma Tarihi -->
                        <div class="service-footer">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $service->created_at->format('d.m.Y') }} tarihinde eklendi
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sayfalama -->
            <div class="mt-4">
                {{ $services->links() }}
            </div>
        @else
            <!-- Boş Durum -->
            <div class="empty-services">
                <div class="text-center py-5">
                    <i class="fas fa-tools fa-5x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">Henüz hizmet eklememişsiniz</h4>
                    <p class="text-muted mb-4">
                        İlk hizmetinizi ekleyerek müşterilere ulaşmaya başlayın!
                    </p>
                    <a href="{{ route('provider.services.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        İlk Hizmetimi Ekle
                    </a>
                </div>
            </div>
        @endif

        <!-- Geri Dön -->
        <div class="text-center mt-4">
            <a href="{{ route('provider.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Panele Dön
            </a>
        </div>
    </div>
</div>
@endsection 