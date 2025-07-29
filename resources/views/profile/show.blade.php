@extends('layouts.app')

@section('title', $user->name . ' - Profil')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile-show.css') }}">
@endpush

@section('content')
<div class="profile-wrapper">
    <div class="container py-5">
        <div class="row">
            <!-- Sol Kolon - Profil Bilgileri -->
            <div class="col-lg-4 mb-4">
                <div class="profile-card">
                    <!-- Profil Fotoğrafı -->
                    <div class="profile-photo-section">
                        <div class="profile-photo">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     alt="{{ $user->name }}" class="img-fluid">
                            @else
                                <div class="no-photo">
                                    <i class="fas fa-user fa-4x"></i>
                                </div>
                            @endif
                        </div>
                        
                        @if($user->is_verified)
                            <div class="verified-badge">
                                <i class="fas fa-check-circle"></i>
                                Doğrulanmış
                            </div>
                        @endif
                    </div>

                    <!-- Temel Bilgiler -->
                    <div class="profile-info">
                        <h3 class="profile-name">{{ $user->name }}</h3>
                        
                        <div class="user-type-badge user-type-{{ $user->user_type }}">
                            @switch($user->user_type)
                                @case('customer')
                                    <i class="fas fa-user me-1"></i> Müşteri
                                    @break
                                @case('service_provider')
                                    <i class="fas fa-tools me-1"></i> Hizmet Sağlayıcı
                                    @break
                                @case('admin')
                                    <i class="fas fa-crown me-1"></i> Yönetici
                                    @break
                            @endswitch
                        </div>

                        @if($user->isServiceProvider() && $user->rating > 0)
                            <div class="rating-section">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($user->rating >= $i)
                                            <i class="fas fa-star"></i>
                                        @elseif($user->rating > $i - 1)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="rating-text">
                                    {{ number_format($user->rating, 1) }} 
                                    <small>({{ $user->total_reviews }} değerlendirme)</small>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- İletişim Bilgileri -->
                    <div class="contact-info">
                        @if($user->phone)
                            <div class="contact-item">
                                <i class="fas fa-phone text-primary"></i>
                                <span>{{ $user->phone }}</span>
                            </div>
                        @endif
                        
                        @if($user->city)
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <span>{{ $user->city }}</span>
                            </div>
                        @endif
                        
                        <div class="contact-item">
                            <i class="fas fa-calendar text-primary"></i>
                            <span>{{ $user->created_at->format('M Y') }} tarihinden beri üye</span>
                        </div>
                    </div>

                    <!-- Profil Düzenleme Butonu -->
                    @if(Auth::id() === $user->id)
                        <div class="profile-actions">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-edit-profile">
                                <i class="fas fa-edit me-2"></i>
                                Profili Düzenle
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sağ Kolon - İçerik -->
            <div class="col-lg-8">
                <!-- Bio -->
                @if($user->bio)
                    <div class="content-card bio-card">
                        <h5 class="card-title">
                            <i class="fas fa-user-alt text-primary me-2"></i>
                            Hakkımda
                        </h5>
                        <p class="bio-text">{{ $user->bio }}</p>
                    </div>
                @endif

                <!-- Hizmetler (Sadece hizmet sağlayıcılar için) -->
                @if($user->isServiceProvider())
                    <div class="content-card services-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-tools text-primary me-2"></i>
                                Hizmetlerim
                            </h5>
                            <span class="badge bg-primary">{{ $user->services->count() }} hizmet</span>
                        </div>

                        @if($user->services->count() > 0)
                            <div class="services-grid">
                                @foreach($user->services as $service)
                                    <div class="service-item">
                                        <div class="service-header">
                                            <h6 class="service-title">
                                                <a href="{{ route('services.show', $service) }}" class="text-decoration-none">
                                                    {{ $service->title }}
                                                </a>
                                            </h6>
                                            <span class="service-category">{{ $service->serviceCategory->name }}</span>
                                        </div>
                                        
                                        <div class="service-details">
                                            @if($service->price_min && $service->price_max)
                                                <div class="service-price">
                                                    <i class="fas fa-lira-sign text-success me-1"></i>
                                                    {{ number_format($service->price_min) }} - {{ number_format($service->price_max) }} TL
                                                </div>
                                            @endif
                                            
                                            <div class="service-stats">
                                                <small class="text-muted">
                                                    <i class="fas fa-eye me-1"></i>{{ $service->views }} görüntülenme
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-services">
                                <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz hizmet eklenmemiş.</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Son Rezervasyonlar -->
                @if(Auth::id() === $user->id)
                    <div class="content-card bookings-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-calendar-check text-primary me-2"></i>
                                @if($user->isServiceProvider())
                                    Son Gelen Rezervasyonlar
                                @else
                                    Son Rezervasyonlarım
                                @endif
                            </h5>
                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary btn-sm">
                                Tümünü Gör
                            </a>
                        </div>

                        @php
                            $recentBookings = $user->isServiceProvider() 
                                ? $user->providerBookings()->with(['service', 'customer'])->latest()->take(3)->get()
                                : $user->customerBookings()->with(['service', 'provider'])->latest()->take(3)->get();
                        @endphp

                        @if($recentBookings->count() > 0)
                            <div class="bookings-list">
                                @foreach($recentBookings as $booking)
                                    <div class="booking-item">
                                        <div class="booking-info">
                                            <h6 class="booking-service">{{ $booking->service->title }}</h6>
                                            <p class="booking-participant">
                                                @if($user->isServiceProvider())
                                                    Müşteri: {{ $booking->customer->name }}
                                                @else
                                                    Sağlayıcı: {{ $booking->provider->name }}
                                                @endif
                                            </p>
                                            <small class="booking-date">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $booking->preferred_date->format('d.m.Y') }}
                                            </small>
                                        </div>
                                        <div class="booking-status">
                                            <span class="status-badge status-{{ $booking->status }}">
                                                @switch($booking->status)
                                                    @case('pending')
                                                        Beklemede
                                                        @break
                                                    @case('accepted')
                                                        Kabul Edildi
                                                        @break
                                                    @case('completed')
                                                        Tamamlandı
                                                        @break
                                                    @default
                                                        {{ ucfirst($booking->status) }}
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-bookings">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz rezervasyon yok.</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- İstatistikler (Hizmet sağlayıcılar için) -->
                @if($user->isServiceProvider())
                    <div class="content-card stats-card">
                        <h5 class="card-title">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            İstatistikler
                        </h5>
                        
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->services->count() }}</div>
                                <div class="stat-label">Aktif Hizmet</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->providerBookings->count() }}</div>
                                <div class="stat-label">Toplam Rezervasyon</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->providerBookings->where('status', 'completed')->count() }}</div>
                                <div class="stat-label">Tamamlanan İş</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $user->total_reviews }}</div>
                                <div class="stat-label">Değerlendirme</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 