@extends('layouts.app')

@section('title', 'Rezervasyonlarım')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/booking-index.css') }}">
@endpush

@section('content')
<div class="bookings-wrapper">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <!-- Başlık -->
                <div class="bookings-header mb-4">
                    <h2 class="bookings-title">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        @if(Auth::user()->isServiceProvider())
                            Gelen Rezervasyonlar
                        @else
                            Rezervasyonlarım
                        @endif
                    </h2>
                    <p class="text-muted">
                        @if(Auth::user()->isServiceProvider())
                            Size gelen rezervasyon taleplerini yönetin
                        @else
                            Yaptığınız rezervasyon taleplerini görüntüleyin
                        @endif
                    </p>
                </div>

                <!-- Filtre Sekmeleri -->
                <div class="status-tabs mb-4">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" data-status="all" href="#" onclick="filterBookings('all')">
                                <i class="fas fa-list me-1"></i>
                                Tümü ({{ $bookings->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="pending" href="#" onclick="filterBookings('pending')">
                                <i class="fas fa-clock me-1"></i>
                                Beklemede ({{ $bookings->where('status', 'pending')->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="accepted" href="#" onclick="filterBookings('accepted')">
                                <i class="fas fa-check-circle me-1"></i>
                                Kabul Edildi ({{ $bookings->where('status', 'accepted')->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="completed" href="#" onclick="filterBookings('completed')">
                                <i class="fas fa-flag-checkered me-1"></i>
                                Tamamlandı ({{ $bookings->where('status', 'completed')->count() }})
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Rezervasyon Listesi -->
                @if($bookings->count() > 0)
                    <div class="bookings-list">
                        @foreach($bookings as $booking)
                            <div class="booking-card" data-status="{{ $booking->status }}">
                                <div class="booking-card-header">
                                    <div class="booking-service">
                                        <h5 class="service-title">
                                            <a href="{{ route('services.show', $booking->service) }}" class="text-decoration-none">
                                                {{ $booking->service->title }}
                                            </a>
                                        </h5>
                                        <p class="service-category text-muted">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $booking->service->serviceCategory->name }}
                                        </p>
                                    </div>
                                    
                                    <div class="booking-status-badge status-{{ $booking->status }}">
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

                                <div class="booking-card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="booking-info">
                                                <div class="info-item">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    <span>{{ $booking->preferred_date->format('d.m.Y') }}</span>
                                                    @if($booking->preferred_time)
                                                        <span class="ms-2">
                                                            <i class="fas fa-clock text-primary me-1"></i>
                                                            {{ $booking->preferred_time->format('H:i') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <div class="info-item">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    <span>{{ Str::limit($booking->address, 50) }}</span>
                                                </div>

                                                @if($booking->quoted_price)
                                                    <div class="info-item">
                                                        <i class="fas fa-lira-sign text-success me-2"></i>
                                                        <span class="text-success fw-semibold">{{ number_format($booking->quoted_price, 2) }} TL</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="participant-info">
                                                @if(Auth::user()->isServiceProvider())
                                                    <!-- Müşteri Bilgisi -->
                                                    <div class="participant">
                                                        <img src="{{ $booking->customer->profile_photo ? asset('storage/' . $booking->customer->profile_photo) : 'https://via.placeholder.com/40' }}" 
                                                             alt="{{ $booking->customer->name }}" class="participant-avatar">
                                                        <div class="participant-details">
                                                            <strong>{{ $booking->customer->name }}</strong>
                                                            <small class="text-muted d-block">Müşteri</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Hizmet Sağlayıcı Bilgisi -->
                                                    <div class="participant">
                                                        <img src="{{ $booking->provider->profile_photo ? asset('storage/' . $booking->provider->profile_photo) : 'https://via.placeholder.com/40' }}" 
                                                             alt="{{ $booking->provider->name }}" class="participant-avatar">
                                                        <div class="participant-details">
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($booking->description)
                                        <div class="booking-description mt-3">
                                            <strong>Açıklama:</strong>
                                            <p class="mb-0">{{ Str::limit($booking->description, 150) }}</p>
                                        </div>
                                    @endif

                                    @if($booking->provider_notes)
                                        <div class="booking-notes mt-2">
                                            <strong>
                                                @if(Auth::user()->isServiceProvider())
                                                    Notlarınız:
                                                @else
                                                    Hizmet Sağlayıcı Notları:
                                                @endif
                                            </strong>
                                            <p class="mb-0 text-muted">{{ Str::limit($booking->provider_notes, 100) }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="booking-card-footer">
                                    <div class="booking-meta">
                                        <small class="text-muted">
                                            <i class="fas fa-plus-circle me-1"></i>
                                            {{ $booking->created_at->format('d.m.Y H:i') }}
                                        </small>
                                        @if($booking->responded_at)
                                            <small class="text-muted ms-3">
                                                <i class="fas fa-reply me-1"></i>
                                                {{ $booking->responded_at->format('d.m.Y H:i') }}
                                            </small>
                                        @endif
                                    </div>

                                    <div class="booking-actions">
                                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            Detay
                                        </a>

                                        @if($booking->status === 'pending' && Auth::user()->isServiceProvider())
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-check me-1"></i>
                                                Yanıtla
                                            </a>
                                        @endif

                                        @if($booking->status === 'accepted' && Auth::user()->isServiceProvider())
                                            <form method="POST" action="{{ route('bookings.complete', $booking) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-flag-checkered me-1"></i>
                                                    Tamamla
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($booking->status, ['pending', 'accepted']) && Auth::user()->isCustomer())
                                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Rezervasyonu iptal etmek istediğinizden emin misiniz?')">
                                                    <i class="fas fa-ban me-1"></i>
                                                    İptal Et
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-bookings">
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">
                                @if(Auth::user()->isServiceProvider())
                                    Henüz rezervasyon talebi yok
                                @else
                                    Henüz rezervasyon yapmadınız
                                @endif
                            </h4>
                            <p class="text-muted">
                                @if(Auth::user()->isServiceProvider())
                                    Hizmetlerinizi yayınladığınızda müşterilerden rezervasyon talepleri gelecektir.
                                @else
                                    Hizmet almak için arama yapabilir ve beğendiğiniz hizmetlerden randevu alabilirsiniz.
                                @endif
                            </p>
                            @if(Auth::user()->isCustomer())
                                <a href="{{ route('services.index') }}" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>
                                    Hizmetleri Keşfet
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function filterBookings(status) {
    // Sekmeleri güncelle
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector(`[data-status="${status}"]`).classList.add('active');
    
    // Kartları filtrele
    document.querySelectorAll('.booking-card').forEach(card => {
        if (status === 'all' || card.getAttribute('data-status') === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection 