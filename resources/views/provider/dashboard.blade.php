@extends('layouts.app')

@section('title', 'Hizmet Sağlayıcı Paneli')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/provider-dashboard.css') }}">
@endpush

@section('content')
<div class="provider-dashboard-wrapper">
    <div class="container-fluid py-4">
        <!-- Başlık -->
        <div class="dashboard-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="dashboard-title">
                        <i class="fas fa-tachometer-alt text-primary me-2"></i>
                        Hizmet Sağlayıcı Paneli
                    </h2>
                    <p class="text-muted">Hoş geldiniz, {{ Auth::user()->name }}!</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="quick-actions">
                        <a href="{{ route('provider.services.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Yeni Hizmet Ekle
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- İstatistik Kartları -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-primary">
                    <div class="stats-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ $totalServices }}</div>
                        <div class="stats-label">Toplam Hizmet</div>
                        <div class="stats-sublabel">{{ $activeServices }} aktif</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-success">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ $totalBookings }}</div>
                        <div class="stats-label">Toplam Rezervasyon</div>
                        <div class="stats-sublabel">
                            @if($lastMonthBookings > 0)
                                @php
                                    $bookingChange = (($thisMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100;
                                @endphp
                                <span class="change {{ $bookingChange >= 0 ? 'positive' : 'negative' }}">
                                    <i class="fas fa-arrow-{{ $bookingChange >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs(round($bookingChange, 1)) }}%
                                </span>
                            @else
                                <span class="text-muted">Bu ay {{ $thisMonthBookings }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-warning">
                    <div class="stats-icon">
                        <i class="fas fa-lira-sign"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ number_format($totalEarnings, 0) }}</div>
                        <div class="stats-label">Toplam Kazanç (TL)</div>
                        <div class="stats-sublabel">
                            Bu ay {{ number_format($thisMonthEarnings, 0) }} TL
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-info">
                    <div class="stats-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ number_format($averageRating, 1) }}</div>
                        <div class="stats-label">Ortalama Puan</div>
                        <div class="stats-sublabel">{{ $totalReviews }} değerlendirme</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sol Kolon -->
            <div class="col-lg-8">
                <!-- Bekleyen Rezervasyonlar -->
                @if($pendingBookings > 0)
                    <div class="dashboard-card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-clock text-warning me-2"></i>
                                Bekleyen Rezervasyonlar ({{ $pendingBookings }})
                            </h5>
                            <a href="{{ route('provider.bookings', ['status' => 'pending']) }}" class="btn btn-outline-primary btn-sm">
                                Tümünü Gör
                            </a>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ $pendingBookings }} rezervasyon yanıtınızı bekliyor!
                            <a href="{{ route('provider.bookings', ['status' => 'pending']) }}" class="alert-link">Hemen kontrol edin</a>
                        </div>
                    </div>
                @endif

                <!-- Son Rezervasyonlar -->
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            Son Rezervasyonlar
                        </h5>
                        <a href="{{ route('provider.bookings') }}" class="btn btn-outline-primary btn-sm">
                            Tümünü Gör
                        </a>
                    </div>
                    <div class="card-body">
                        @if($recentBookings->count() > 0)
                            <div class="bookings-list">
                                @foreach($recentBookings as $booking)
                                    <div class="booking-item">
                                        <div class="booking-info">
                                            <h6 class="booking-service">{{ $booking->service->title }}</h6>
                                            <p class="booking-customer">
                                                <i class="fas fa-user text-muted me-1"></i>
                                                {{ $booking->customer->name }}
                                            </p>
                                            <small class="booking-date">
                                                <i class="fas fa-calendar text-muted me-1"></i>
                                                {{ $booking->preferred_date->format('d.m.Y') }}
                                            </small>
                                        </div>
                                        <div class="booking-status">
                                            <span class="status-badge status-{{ $booking->status }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz rezervasyon yok</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- En Performanslı Hizmetler -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-chart-line text-success me-2"></i>
                            En Performanslı Hizmetler
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($topServices->count() > 0)
                            <div class="services-list">
                                @foreach($topServices as $service)
                                    <div class="service-item">
                                        <div class="service-info">
                                            <h6 class="service-title">{{ $service->title }}</h6>
                                            <p class="service-category">{{ $service->serviceCategory->name }}</p>
                                        </div>
                                        <div class="service-stats">
                                            <div class="stat-item">
                                                <span class="stat-number">{{ $service->bookings_count }}</span>
                                                <span class="stat-label">Rezervasyon</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-number">{{ $service->views }}</span>
                                                <span class="stat-label">Görüntülenme</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz hizmet verisi yok</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sağ Kolon -->
            <div class="col-lg-4">
                <!-- Hızlı Erişim -->
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-rocket text-primary me-2"></i>
                            Hızlı Erişim
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-links">
                            <a href="{{ route('provider.services.create') }}" class="quick-link">
                                <i class="fas fa-plus"></i>
                                <span>Yeni Hizmet Ekle</span>
                            </a>
                            <a href="{{ route('provider.services') }}" class="quick-link">
                                <i class="fas fa-tools"></i>
                                <span>Hizmetlerimi Yönet</span>
                            </a>
                            <a href="{{ route('provider.bookings') }}" class="quick-link">
                                <i class="fas fa-calendar-check"></i>
                                <span>Rezervasyonları Gör</span>
                            </a>
                            <a href="{{ route('provider.reviews') }}" class="quick-link">
                                <i class="fas fa-star"></i>
                                <span>Değerlendirmelerim</span>
                            </a>
                            <a href="{{ route('provider.analytics') }}" class="quick-link">
                                <i class="fas fa-chart-bar"></i>
                                <span>Analitik Raporlar</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Son Değerlendirmeler -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-star text-warning me-2"></i>
                            Son Değerlendirmeler
                        </h5>
                        <a href="{{ route('provider.reviews') }}" class="btn btn-outline-primary btn-sm">
                            Tümünü Gör
                        </a>
                    </div>
                    <div class="card-body">
                        @if($recentReviews->count() > 0)
                            <div class="reviews-list">
                                @foreach($recentReviews as $review)
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-info">
                                                <strong>{{ $review->reviewer->name }}</strong>
                                                <small class="text-muted">{{ $review->created_at->format('d.m.Y') }}</small>
                                            </div>
                                            <div class="review-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="review-comment">{{ Str::limit($review->comment, 100) }}</p>
                                        <small class="text-muted">{{ $review->booking->service->title }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz değerlendirme yok</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 