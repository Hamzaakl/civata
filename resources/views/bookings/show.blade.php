@extends('layouts.app')

@section('title', 'Rezervasyon Detayı')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/booking-show.css') }}">
@endpush

@section('content')
<div class="booking-detail-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Başlık ve Durum -->
                <div class="booking-header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="booking-title">
                            <i class="fas fa-calendar-check text-primary me-2"></i>
                            Rezervasyon Detayı
                        </h2>
                        <div class="booking-status status-{{ $booking->status }}">
                            @switch($booking->status)
                                @case('pending')
                                    <i class="fas fa-clock me-1"></i> Beklemede
                                    @break
                                @case('accepted')
                                    <i class="fas fa-check-circle me-1"></i> Kabul Edildi
                                    @break
                                @case('rejected')
                                    <i class="fas fa-times-circle me-1"></i> Reddedildi
                                    @break
                                @case('completed')
                                    <i class="fas fa-flag-checkered me-1"></i> Tamamlandı
                                    @break
                                @case('cancelled')
                                    <i class="fas fa-ban me-1"></i> İptal Edildi
                                    @break
                            @endswitch
                        </div>
                    </div>
                    <p class="text-muted">Rezervasyon ID: #{{ $booking->id }}</p>
                </div>

                <div class="row">
                    <!-- Rezervasyon Bilgileri -->
                    <div class="col-md-8 mb-4">
                        <div class="booking-info-card">
                            <h5 class="card-title">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Rezervasyon Bilgileri
                            </h5>

                            <div class="info-group">
                                <label>Hizmet:</label>
                                <div class="info-value">
                                    <a href="{{ route('services.show', $booking->service) }}" class="text-decoration-none">
                                        {{ $booking->service->title }}
                                    </a>
                                    <small class="text-muted d-block">{{ $booking->service->serviceCategory->name }}</small>
                                </div>
                            </div>

                            <div class="info-group">
                                <label>Açıklama:</label>
                                <div class="info-value">{{ $booking->description }}</div>
                            </div>

                            <div class="info-group">
                                <label>Hizmet Adresi:</label>
                                <div class="info-value">{{ $booking->address }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label>Tercih Edilen Tarih:</label>
                                        <div class="info-value">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $booking->preferred_date->format('d.m.Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label>Tercih Edilen Saat:</label>
                                        <div class="info-value">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $booking->preferred_time ? $booking->preferred_time->format('H:i') : 'Belirtilmedi' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($booking->quoted_price)
                                <div class="info-group">
                                    <label>Teklif Edilen Fiyat:</label>
                                    <div class="info-value text-success">
                                        <i class="fas fa-lira-sign me-1"></i>
                                        {{ number_format($booking->quoted_price, 2) }} TL
                                    </div>
                                </div>
                            @endif

                            @if($booking->provider_notes)
                                <div class="info-group">
                                    <label>Hizmet Sağlayıcı Notları:</label>
                                    <div class="info-value">{{ $booking->provider_notes }}</div>
                                </div>
                            @endif

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-plus-circle me-1"></i>
                                        Oluşturulma: {{ $booking->created_at->format('d.m.Y H:i') }}
                                    </small>
                                </div>
                                @if($booking->responded_at)
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fas fa-reply me-1"></i>
                                            Cevaplandı: {{ $booking->responded_at->format('d.m.Y H:i') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Taraf Bilgileri -->
                    <div class="col-md-4 mb-4">
                        <div class="parties-card">
                            <h5 class="card-title">
                                <i class="fas fa-users text-primary me-2"></i>
                                Taraflar
                            </h5>

                            <!-- Müşteri -->
                            <div class="party-info">
                                <div class="party-label">Müşteri:</div>
                                <div class="party-details">
                                    <img src="{{ $booking->customer->profile_photo ? asset('storage/' . $booking->customer->profile_photo) : 'https://via.placeholder.com/40' }}" 
                                         alt="{{ $booking->customer->name }}" class="party-avatar">
                                    <div>
                                        <strong>{{ $booking->customer->name }}</strong>
                                        @if(Auth::id() === $booking->provider_id && $booking->customer->phone)
                                            <div class="contact-info">
                                                <i class="fas fa-phone text-muted me-1"></i>
                                                <small>{{ $booking->customer->phone }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Hizmet Sağlayıcı -->
                            <div class="party-info">
                                <div class="party-label">Hizmet Sağlayıcı:</div>
                                <div class="party-details">
                                    <img src="{{ $booking->provider->profile_photo ? asset('storage/' . $booking->provider->profile_photo) : 'https://via.placeholder.com/40' }}" 
                                         alt="{{ $booking->provider->name }}" class="party-avatar">
                                    <div>
                                        <strong>{{ $booking->provider->name }}</strong>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($booking->provider->rating >= $i)
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($booking->provider->rating > $i - 1)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <small class="text-muted">({{ $booking->provider->total_reviews }})</small>
                                        </div>
                                        @if(Auth::id() === $booking->customer_id && $booking->provider->phone)
                                            <div class="contact-info">
                                                <i class="fas fa-phone text-muted me-1"></i>
                                                <small>{{ $booking->provider->phone }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aksiyon Butonları -->
                @if($booking->status === 'pending' && Auth::id() === $booking->provider_id)
                    <div class="action-section">
                        <div class="action-card">
                            <h5 class="card-title">
                                <i class="fas fa-cogs text-primary me-2"></i>
                                Rezervasyon İşlemleri
                            </h5>
                            
                            <!-- Kabul Et Formu -->
                            <form method="POST" action="{{ route('bookings.accept', $booking) }}" class="mb-3">
                                @csrf
                                @method('PATCH')
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="quoted_price" class="form-label">Teklif Fiyatı (TL)</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="quoted_price" 
                                               name="quoted_price" 
                                               step="0.01" 
                                               min="0"
                                               placeholder="Örn: 150.00">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="provider_notes" class="form-label">Not (Opsiyonel)</label>
                                    <textarea class="form-control" 
                                              id="provider_notes" 
                                              name="provider_notes" 
                                              rows="3" 
                                              placeholder="Rezervasyon hakkında notlarınız..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-check me-1"></i>
                                    Kabul Et
                                </button>
                            </form>

                            <!-- Red Et Formu -->
                            <form method="POST" action="{{ route('bookings.reject', $booking) }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-3">
                                    <label for="reject_notes" class="form-label">Red Sebebi</label>
                                    <textarea class="form-control" 
                                              id="reject_notes" 
                                              name="provider_notes" 
                                              rows="2" 
                                              placeholder="Lütfen red sebebinizi belirtin..." 
                                              required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-1"></i>
                                    Reddet
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($booking->status === 'accepted' && Auth::id() === $booking->provider_id)
                    <div class="action-section">
                        <div class="action-card">
                            <form method="POST" action="{{ route('bookings.complete', $booking) }}">
                                @csrf
                                @method('PATCH')
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-flag-checkered me-1"></i>
                                    Hizmeti Tamamlandı Olarak İşaretle
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($booking->status === 'completed' && Auth::id() === $booking->customer_id)
                    <div class="action-section">
                        <div class="action-card">
                            @php
                                $existingReview = App\Models\Review::where('booking_id', $booking->id)
                                                                  ->where('reviewer_id', Auth::id())
                                                                  ->first();
                            @endphp
                            
                            @if($existingReview)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Bu hizmet için değerlendirmenizi yapmışsınız!
                                </div>
                                <a href="{{ route('reviews.show', $existingReview) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-star me-1"></i>
                                    Değerlendirmenizi Görüntüle
                                </a>
                            @else
                                <div class="mb-3">
                                    <h6><i class="fas fa-star text-warning me-2"></i>Hizmet Değerlendirmesi</h6>
                                    <p class="text-muted mb-3">Aldığınız hizmet tamamlandı. Deneyiminizi paylaşarak diğer kullanıcılara yardımcı olun!</p>
                                </div>
                                <a href="{{ route('reviews.create', $booking) }}" class="btn btn-warning">
                                    <i class="fas fa-star me-1"></i>
                                    Değerlendirme Yap
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if(in_array($booking->status, ['pending', 'accepted']) && Auth::id() === $booking->customer_id)
                    <div class="action-section">
                        <div class="action-card">
                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                                @csrf
                                @method('PATCH')
                                
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Rezervasyonu iptal etmek istediğinizden emin misiniz?')">
                                    <i class="fas fa-ban me-1"></i>
                                    Rezervasyonu İptal Et
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Geri Dön -->
                <div class="text-center mt-4">
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Rezervasyonlarım
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 