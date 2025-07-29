@extends('layouts.app')

@section('title', 'Admin Paneli')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')
<div class="admin-dashboard-wrapper">
    <div class="container-fluid py-4">
        <!-- Başlık -->
        <div class="dashboard-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="dashboard-title">
                        <i class="fas fa-cog text-danger me-2"></i>
                        Admin Paneli
                    </h2>
                    <p class="text-muted">Platform yönetim merkezi</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="quick-actions">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-success me-2">
                            <i class="fas fa-plus me-1"></i>
                            Kategori Ekle
                        </a>
                        <a href="{{ route('admin.analytics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar me-1"></i>
                            Detaylı Analitik
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Genel İstatistikler -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-users">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ number_format($totalUsers) }}</div>
                        <div class="stats-label">Toplam Kullanıcı</div>
                        <div class="stats-sublabel">
                            {{ $totalCustomers }} müşteri, {{ $totalProviders }} sağlayıcı
                        </div>
                        <div class="stats-change">
                            <i class="fas fa-arrow-up text-success"></i>
                            <span class="text-success">+{{ $newUsersThisMonth }} bu ay</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-services">
                    <div class="stats-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ number_format($totalServices) }}</div>
                        <div class="stats-label">Toplam Hizmet</div>
                        <div class="stats-sublabel">{{ $activeServices }} aktif hizmet</div>
                        <div class="stats-change">
                            <i class="fas fa-arrow-up text-success"></i>
                            <span class="text-success">+{{ $newServicesThisMonth }} bu ay</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-bookings">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ number_format($totalBookings) }}</div>
                        <div class="stats-label">Toplam Rezervasyon</div>
                        <div class="stats-sublabel">{{ $completedBookings }} tamamlandı</div>
                        <div class="stats-change">
                            <i class="fas fa-arrow-up text-success"></i>
                            <span class="text-success">+{{ $bookingsThisMonth }} bu ay</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-revenue">
                    <div class="stats-icon">
                        <i class="fas fa-lira-sign"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ number_format($totalEarnings, 0) }}</div>
                        <div class="stats-label">Toplam Ciro (TL)</div>
                        <div class="stats-sublabel">{{ $totalReviews }} değerlendirme</div>
                        <div class="stats-change">
                            <i class="fas fa-chart-line text-info"></i>
                            <span class="text-info">Platform büyüyor</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sol Kolon -->
            <div class="col-lg-8">
                <!-- Uyarılar -->
                @if($pendingServices > 0)
                    <div class="alert-card alert-warning mb-4">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <h6>Onay Bekleyen Hizmetler</h6>
                            <p>{{ $pendingServices }} hizmet incelemenizi bekliyor!</p>
                            <a href="{{ route('admin.services', ['is_active' => 0]) }}" class="btn btn-warning btn-sm">
                                Hemen İncele
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Son Aktiviteler -->
                <div class="admin-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-clock text-primary me-2"></i>
                            Son Aktiviteler
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-tabs">
                            <ul class="nav nav-pills mb-3" id="activityTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="users-tab" data-bs-toggle="pill" data-bs-target="#users" type="button">
                                        <i class="fas fa-users me-1"></i>Yeni Kullanıcılar
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="services-tab" data-bs-toggle="pill" data-bs-target="#services" type="button">
                                        <i class="fas fa-tools me-1"></i>Yeni Hizmetler
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="bookings-tab" data-bs-toggle="pill" data-bs-target="#bookings" type="button">
                                        <i class="fas fa-calendar me-1"></i>Son Rezervasyonlar
                                    </button>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="activityTabsContent">
                                <!-- Yeni Kullanıcılar -->
                                <div class="tab-pane fade show active" id="users" role="tabpanel">
                                    @if($recentUsers->count() > 0)
                                        <div class="activity-list">
                                            @foreach($recentUsers as $user)
                                                <div class="activity-item">
                                                    <div class="activity-avatar">
                                                        @if($user->profile_photo)
                                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                                        @else
                                                            <div class="avatar-placeholder">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="activity-content">
                                                        <h6>{{ $user->name }}</h6>
                                                        <p class="text-muted">{{ $user->email }}</p>
                                                        <div class="activity-meta">
                                                            <span class="user-type-badge user-type-{{ $user->user_type }}">
                                                                {{ $user->user_type === 'customer' ? 'Müşteri' : 'Hizmet Sağlayıcı' }}
                                                            </span>
                                                            <small class="text-muted ms-2">
                                                                {{ $user->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="activity-actions">
                                                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary btn-sm">
                                                            Görüntüle
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Henüz yeni kullanıcı yok</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Yeni Hizmetler -->
                                <div class="tab-pane fade" id="services" role="tabpanel">
                                    @if($recentServices->count() > 0)
                                        <div class="activity-list">
                                            @foreach($recentServices as $service)
                                                <div class="activity-item">
                                                    <div class="activity-avatar">
                                                        @if($service->images && count(json_decode($service->images)) > 0)
                                                            <img src="{{ asset('storage/' . json_decode($service->images)[0]) }}" alt="{{ $service->title }}">
                                                        @else
                                                            <div class="avatar-placeholder">
                                                                <i class="fas fa-tools"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="activity-content">
                                                        <h6>{{ $service->title }}</h6>
                                                        <p class="text-muted">{{ $service->user->name }} - {{ $service->serviceCategory->name }}</p>
                                                        <div class="activity-meta">
                                                            <span class="status-badge status-{{ $service->is_active ? 'active' : 'inactive' }}">
                                                                {{ $service->is_active ? 'Aktif' : 'Beklemede' }}
                                                            </span>
                                                            <small class="text-muted ms-2">
                                                                {{ $service->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="activity-actions">
                                                        <a href="{{ route('services.show', $service) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                                            Görüntüle
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Henüz yeni hizmet yok</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Son Rezervasyonlar -->
                                <div class="tab-pane fade" id="bookings" role="tabpanel">
                                    @if($recentBookings->count() > 0)
                                        <div class="activity-list">
                                            @foreach($recentBookings as $booking)
                                                <div class="activity-item">
                                                    <div class="activity-avatar">
                                                        @if($booking->customer->profile_photo)
                                                            <img src="{{ asset('storage/' . $booking->customer->profile_photo) }}" alt="{{ $booking->customer->name }}">
                                                        @else
                                                            <div class="avatar-placeholder">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="activity-content">
                                                        <h6>{{ $booking->service->title }}</h6>
                                                        <p class="text-muted">
                                                            {{ $booking->customer->name }} → {{ $booking->provider->name }}
                                                        </p>
                                                        <div class="activity-meta">
                                                            <span class="status-badge status-{{ $booking->status }}">
                                                                {{ ucfirst($booking->status) }}
                                                            </span>
                                                            <small class="text-muted ms-2">
                                                                {{ $booking->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="activity-actions">
                                                        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-primary btn-sm">
                                                            Detay
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Henüz rezervasyon yok</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ Kolon -->
            <div class="col-lg-4">
                <!-- Hızlı Erişim -->
                <div class="admin-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-rocket text-primary me-2"></i>
                            Hızlı Erişim
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-links">
                            <a href="{{ route('admin.users') }}" class="quick-link">
                                <i class="fas fa-users"></i>
                                <span>Kullanıcı Yönetimi</span>
                                <small class="badge bg-primary">{{ $totalUsers }}</small>
                            </a>
                            <a href="{{ route('admin.categories') }}" class="quick-link">
                                <i class="fas fa-list"></i>
                                <span>Kategori Yönetimi</span>
                            </a>
                            <a href="{{ route('admin.services') }}" class="quick-link">
                                <i class="fas fa-tools"></i>
                                <span>Hizmet Yönetimi</span>
                                <small class="badge bg-warning">{{ $pendingServices }}</small>
                            </a>
                            <a href="{{ route('admin.bookings') }}" class="quick-link">
                                <i class="fas fa-calendar-check"></i>
                                <span>Rezervasyon Takibi</span>
                                <small class="badge bg-success">{{ $totalBookings }}</small>
                            </a>
                            <a href="{{ route('admin.reviews') }}" class="quick-link">
                                <i class="fas fa-star"></i>
                                <span>Değerlendirme Yönetimi</span>
                                <small class="badge bg-info">{{ $totalReviews }}</small>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kategori Performansı -->
                <div class="admin-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-chart-pie text-success me-2"></i>
                            Kategori Performansı
                        </h5>
                        <a href="{{ route('admin.categories') }}" class="btn btn-outline-success btn-sm">
                            Tümünü Gör
                        </a>
                    </div>
                    <div class="card-body">
                        @if($categoryStats->count() > 0)
                            <div class="category-stats">
                                @foreach($categoryStats->take(6) as $category)
                                    <div class="category-stat-item">
                                        <div class="category-icon" style="color: {{ $category->color }};">
                                            <i class="{{ $category->icon }}"></i>
                                        </div>
                                        <div class="category-info">
                                            <h6>{{ $category->name }}</h6>
                                            <div class="category-numbers">
                                                <small class="text-muted">
                                                    {{ $category->services_count }} hizmet • 
                                                    {{ $category->bookings_count }} rezervasyon
                                                </small>
                                            </div>
                                        </div>
                                        <div class="category-progress">
                                            @php
                                                $maxBookings = $categoryStats->max('bookings_count');
                                                $percentage = $maxBookings > 0 ? ($category->bookings_count / $maxBookings) * 100 : 0;
                                            @endphp
                                            <div class="progress">
                                                <div class="progress-bar" style="width: {{ $percentage }}%; background-color: {{ $category->color }};"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Henüz kategori yok</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 