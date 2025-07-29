@extends('layouts.app')

@section('title', $service->title . ' - Civata')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Hizmet Detayları -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary fs-6">{{ $service->serviceCategory->name }}</span>
                        @if($service->is_featured)
                            <span class="badge bg-warning text-dark fs-6">
                                <i class="fas fa-star"></i> Öne Çıkan
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="h2 mb-3">{{ $service->title }}</h1>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-tag fa-2x text-primary mb-2"></i>
                                <h6>Fiyat</h6>
                                <div class="text-primary fw-bold">{{ $service->price_range }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-map-marker-alt fa-2x text-primary mb-2"></i>
                                <h6>Hizmet Alanı</h6>
                                <div>{{ $service->service_area }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                                <h6>Görüntülenme</h6>
                                <div>{{ $service->views }} kez</div>
                            </div>
                        </div>
                    </div>
                    
                    <h5>Hizmet Açıklaması</h5>
                    <p class="text-muted">{{ $service->description }}</p>
                </div>
            </div>
            
            <!-- Benzer Hizmetler -->
            @if($relatedServices->count() > 0)
                <div class="mt-5">
                    <h4 class="mb-4">Benzer Hizmetler</h4>
                    <div class="row g-3">
                        @foreach($relatedServices as $related)
                            <div class="col-md-6">
                                <div class="card service-card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('services.show', $related->id) }}" 
                                               class="text-decoration-none">
                                                {{ $related->title }}
                                            </a>
                                        </h6>
                                        <p class="card-text text-muted small">
                                            {{ Str::limit($related->description, 80) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-primary small fw-bold">{{ $related->price_range }}</div>
                                            <small class="text-muted">{{ $related->user->city }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Hizmet Sağlayıcı Bilgileri -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($service->user->name, 0, 1) }}
                    </div>
                    
                    <h5>
                    <a href="{{ route('users.show', $service->user) }}" class="text-decoration-none">
                        {{ $service->user->name }}
                        @if($service->user->is_verified)
                            <i class="fas fa-check-circle text-success ms-1" style="font-size: 14px;"></i>
                        @endif
                    </a>
                </h5>
                    
                    <div class="rating-stars mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $service->user->rating ? '' : '-o' }}"></i>
                        @endfor
                        <div class="small text-muted mt-1">
                            {{ number_format($service->user->rating, 1) }} / 5.0 
                            ({{ $service->user->total_reviews }} değerlendirme)
                        </div>
                    </div>
                    
                    @if($service->user->bio)
                        <p class="text-muted small">{{ $service->user->bio }}</p>
                    @endif
                    
                    <div class="border-top pt-3 mt-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <i class="fas fa-map-marker-alt text-muted"></i>
                                <div class="small">{{ $service->user->city }}</div>
                            </div>
                            <div class="col-6">
                                <i class="fas fa-phone text-muted"></i>
                                <div class="small">{{ $service->user->phone ?? 'Telefon yok' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="fas fa-envelope"></i> İletişime Geç
                        </button>
                        
                        @auth
                            @if(Auth::user()->isCustomer())
                                <a href="{{ route('bookings.create', $service) }}" class="btn btn-success">
                                    <i class="fas fa-calendar-check"></i> Randevu Al
                                </a>
                            @elseif(Auth::id() === $service->user_id)
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-info-circle"></i> Bu sizin hizmetiniz
                                </button>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-ban"></i> Sadece müşteriler randevu alabilir
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success">
                                <i class="fas fa-calendar-check"></i> Randevu Al (Giriş Gerekli)
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            
            <!-- Diğer Hizmetleri -->
            @php
                $otherServices = $service->user->services()
                    ->where('id', '!=', $service->id)
                    ->active()
                    ->take(3)
                    ->get();
            @endphp
            
            @if($otherServices->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Bu Sağlayıcının Diğer Hizmetleri</h6>
                    </div>
                    <div class="card-body">
                        @foreach($otherServices as $other)
                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <h6 class="mb-1">
                                    <a href="{{ route('services.show', $other->id) }}" 
                                       class="text-decoration-none">
                                        {{ $other->title }}
                                    </a>
                                </h6>
                                <div class="text-primary small fw-bold">{{ $other->price_range }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- İletişim Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">İletişime Geç</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bu özellik yakında aktif olacak. Şimdilik telefon numarasını kullanarak iletişime geçebilirsiniz:</p>
                <div class="alert alert-info">
                    <i class="fas fa-phone"></i> {{ $service->user->phone ?? 'Telefon bilgisi mevcut değil' }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Randevu Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Randevu Al</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Randevu sistemi yakında aktif olacak. Şimdilik telefon numarasını kullanarak randevu alabilirsiniz:</p>
                <div class="alert alert-info">
                    <i class="fas fa-phone"></i> {{ $service->user->phone ?? 'Telefon bilgisi mevcut değil' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 