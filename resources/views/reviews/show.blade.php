@extends('layouts.app')

@section('title', 'Değerlendirme Detayı')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/review-show.css') }}">
@endpush

@section('content')
<div class="review-show-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Başlık -->
                <div class="review-header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="review-title">
                            <i class="fas fa-star text-warning me-2"></i>
                            Hizmet Değerlendirmesi
                        </h2>
                        <div class="review-date">
                            <i class="fas fa-clock text-muted me-1"></i>
                            <small class="text-muted">{{ $review->created_at->format('d.m.Y H:i') }}</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Sol Kolon - Ana Değerlendirme -->
                    <div class="col-lg-8 mb-4">
                        <div class="review-main-card">
                            <!-- Rating -->
                            <div class="rating-section">
                                <div class="rating-display">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($review->rating >= $i)
                                                <i class="fas fa-star"></i>
                                            @elseif($review->rating > $i - 1)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="rating-score">{{ $review->rating }}/5</div>
                                </div>
                                <div class="rating-text">
                                    @switch($review->rating)
                                        @case(1)
                                            Çok Kötü
                                            @break
                                        @case(2)
                                            Kötü
                                            @break
                                        @case(3)
                                            Orta
                                            @break
                                        @case(4)
                                            İyi
                                            @break
                                        @case(5)
                                            Mükemmel
                                            @break
                                    @endswitch
                                </div>
                            </div>

                            <!-- Yorum -->
                            <div class="comment-section">
                                <h6 class="comment-title">
                                    <i class="fas fa-comment text-primary me-2"></i>
                                    Değerlendirme
                                </h6>
                                <p class="comment-text">{{ $review->comment }}</p>
                            </div>

                            <!-- Fotoğraflar -->
                            @if($review->images && count(json_decode($review->images)) > 0)
                                <div class="photos-section">
                                    <h6 class="photos-title">
                                        <i class="fas fa-camera text-primary me-2"></i>
                                        Fotoğraflar
                                    </h6>
                                    <div class="photos-grid">
                                        @foreach(json_decode($review->images) as $index => $image)
                                            <div class="photo-item">
                                                <img src="{{ asset('storage/' . $image) }}" 
                                                     alt="Değerlendirme Fotoğrafı {{ $index + 1 }}" 
                                                     class="review-photo" 
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#imageModal{{ $index }}">
                                                
                                                <!-- Fotoğraf Modal -->
                                                <div class="modal fade" id="imageModal{{ $index }}" tabindex="-1">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Değerlendirme Fotoğrafı</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <img src="{{ asset('storage/' . $image) }}" 
                                                                     alt="Değerlendirme Fotoğrafı" 
                                                                     class="img-fluid">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Aksiyon Butonları -->
                            @if(Auth::check() && Auth::id() === $review->reviewer_id)
                                <div class="review-actions">
                                    @if($review->created_at->diffInHours(now()) <= 24)
                                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i>
                                            Düzenle
                                        </a>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                onclick="return confirm('Değerlendirmeyi silmek istediğinizden emin misiniz?')">
                                            <i class="fas fa-trash me-1"></i>
                                            Sil
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sağ Kolon - Detaylar -->
                    <div class="col-lg-4">
                        <!-- Değerlendiren -->
                        <div class="reviewer-card mb-4">
                            <h6 class="card-title">
                                <i class="fas fa-user text-primary me-2"></i>
                                Değerlendiren
                            </h6>
                            <div class="reviewer-info">
                                <img src="{{ $review->reviewer->profile_photo ? asset('storage/' . $review->reviewer->profile_photo) : 'https://via.placeholder.com/50' }}" 
                                     alt="{{ $review->reviewer->name }}" class="reviewer-avatar">
                                <div class="reviewer-details">
                                    <strong>{{ $review->reviewer->name }}</strong>
                                    @if($review->reviewer->is_verified)
                                        <i class="fas fa-check-circle text-success ms-1" style="font-size: 12px;"></i>
                                    @endif
                                    <div class="reviewer-since">
                                        <small class="text-muted">{{ $review->reviewer->created_at->format('M Y') }} tarihinden beri üye</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hizmet Sağlayıcı -->
                        <div class="reviewee-card mb-4">
                            <h6 class="card-title">
                                <i class="fas fa-user-tie text-primary me-2"></i>
                                Hizmet Sağlayıcı
                            </h6>
                            <div class="reviewee-info">
                                <img src="{{ $review->reviewee->profile_photo ? asset('storage/' . $review->reviewee->profile_photo) : 'https://via.placeholder.com/50' }}" 
                                     alt="{{ $review->reviewee->name }}" class="reviewee-avatar">
                                <div class="reviewee-details">
                                    <strong>
                                        <a href="{{ route('users.show', $review->reviewee) }}" class="text-decoration-none">
                                            {{ $review->reviewee->name }}
                                        </a>
                                    </strong>
                                    @if($review->reviewee->is_verified)
                                        <i class="fas fa-check-circle text-success ms-1" style="font-size: 12px;"></i>
                                    @endif
                                    <div class="reviewee-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($review->reviewee->rating >= $i)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif($review->reviewee->rating > $i - 1)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <small class="text-muted">({{ $review->reviewee->total_reviews }} değerlendirme)</small>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('reviews.provider', $review->reviewee) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-star me-1"></i>
                                            Tüm Değerlendirmeleri Gör
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hizmet Bilgileri -->
                        <div class="service-card mb-4">
                            <h6 class="card-title">
                                <i class="fas fa-tools text-primary me-2"></i>
                                Hizmet Bilgileri
                            </h6>
                            <div class="service-info">
                                <h6 class="service-name">
                                    <a href="{{ route('services.show', $review->booking->service) }}" class="text-decoration-none">
                                        {{ $review->booking->service->title }}
                                    </a>
                                </h6>
                                <p class="service-category">{{ $review->booking->service->serviceCategory->name }}</p>
                                
                                <div class="service-details">
                                    <div class="detail-item">
                                        <i class="fas fa-calendar text-muted me-2"></i>
                                        <span>{{ $review->booking->preferred_date->format('d.m.Y') }}</span>
                                    </div>
                                    
                                    @if($review->booking->preferred_time)
                                        <div class="detail-item">
                                            <i class="fas fa-clock text-muted me-2"></i>
                                            <span>{{ $review->booking->preferred_time->format('H:i') }}</span>
                                        </div>
                                    @endif

                                    @if($review->booking->quoted_price)
                                        <div class="detail-item">
                                            <i class="fas fa-lira-sign text-success me-2"></i>
                                            <span class="text-success">{{ number_format($review->booking->quoted_price, 2) }} TL</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Rezervasyon Detayı -->
                        <div class="text-center">
                            <a href="{{ route('bookings.show', $review->booking) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-calendar-check me-1"></i>
                                Rezervasyon Detayı
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Geri Dön -->
                <div class="text-center mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 