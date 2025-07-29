@extends('layouts.app')

@section('title', 'Değerlendirme Yap')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/review-create.css') }}">
@endpush

@section('content')
<div class="review-create-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Başlık -->
                <div class="page-header mb-4">
                    <h2 class="page-title">
                        <i class="fas fa-star text-warning me-2"></i>
                        Hizmet Değerlendirmesi
                    </h2>
                    <p class="text-muted">Aldığınız hizmet hakkında değerlendirme yapın</p>
                </div>

                <div class="row">
                    <!-- Hizmet Bilgileri -->
                    <div class="col-md-4 mb-4">
                        <div class="service-info-card">
                            <div class="service-header">
                                <h6 class="service-title">{{ $booking->service->title }}</h6>
                                <span class="service-category">{{ $booking->service->serviceCategory->name }}</span>
                            </div>

                            <div class="booking-details">
                                <div class="detail-item">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <span>{{ $booking->preferred_date->format('d.m.Y') }}</span>
                                </div>
                                
                                @if($booking->preferred_time)
                                    <div class="detail-item">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <span>{{ $booking->preferred_time->format('H:i') }}</span>
                                    </div>
                                @endif

                                @if($booking->quoted_price)
                                    <div class="detail-item">
                                        <i class="fas fa-lira-sign text-success me-2"></i>
                                        <span class="text-success fw-semibold">{{ number_format($booking->quoted_price, 2) }} TL</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Hizmet Sağlayıcı -->
                            <div class="provider-info">
                                <div class="provider-header">
                                    <i class="fas fa-user-tie text-primary me-2"></i>
                                    <span class="fw-semibold">Hizmet Sağlayıcı</span>
                                </div>
                                <div class="provider-details">
                                    <img src="{{ $booking->provider->profile_photo ? asset('storage/' . $booking->provider->profile_photo) : 'https://via.placeholder.com/40' }}" 
                                         alt="{{ $booking->provider->name }}" class="provider-avatar">
                                    <div>
                                        <div class="provider-name">{{ $booking->provider->name }}</div>
                                        @if($booking->provider->rating > 0)
                                            <div class="provider-rating">
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
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Değerlendirme Formu -->
                    <div class="col-md-8">
                        <div class="review-form-card">
                            <form method="POST" action="{{ route('reviews.store', $booking) }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Puan Verme -->
                                <div class="rating-section mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        Hizmet Puanı
                                    </label>
                                    <div class="star-rating" id="starRating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="star" data-rating="{{ $i }}">
                                                <i class="far fa-star"></i>
                                            </span>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}">
                                    <div class="rating-text" id="ratingText">
                                        Lütfen bir puan seçin
                                    </div>
                                    @error('rating')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Yorum -->
                                <div class="mb-4">
                                    <label for="comment" class="form-label fw-semibold">
                                        <i class="fas fa-comment text-primary me-2"></i>
                                        Değerlendirmeniz
                                    </label>
                                    <textarea class="form-control review-input @error('comment') is-invalid @enderror" 
                                              id="comment" 
                                              name="comment" 
                                              rows="5" 
                                              placeholder="Aldığınız hizmet hakkında detaylı görüşlerinizi paylaşın..."
                                              required>{{ old('comment') }}</textarea>
                                    <small class="form-text text-muted">En az 10 karakter girmelisiniz.</small>
                                    @error('comment')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Fotoğraf Yükleme -->
                                <div class="mb-4">
                                    <label for="images" class="form-label fw-semibold">
                                        <i class="fas fa-camera text-primary me-2"></i>
                                        Fotoğraflar <small class="text-muted">(Opsiyonel)</small>
                                    </label>
                                    <div class="photo-upload-area">
                                        <input type="file" 
                                               class="form-control @error('images.*') is-invalid @enderror" 
                                               id="images" 
                                               name="images[]" 
                                               multiple 
                                               accept="image/*">
                                        <small class="form-text text-muted">
                                            JPG, PNG, GIF formatında maksimum 5 fotoğraf yükleyebilirsiniz. Her biri en fazla 2MB olmalıdır.
                                        </small>
                                    </div>
                                    @error('images.*')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Fotoğraf Önizleme -->
                                <div class="photo-preview" id="photoPreview" style="display: none;">
                                    <label class="form-label fw-semibold">Seçilen Fotoğraflar:</label>
                                    <div class="preview-container" id="previewContainer"></div>
                                </div>

                                <!-- Bilgi Kutusu -->
                                <div class="info-box mb-4">
                                    <div class="d-flex">
                                        <div class="info-icon">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div class="info-content">
                                            <strong>Değerlendirme Kuralları:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Değerlendirmeniz yayınlandıktan sonra 24 saat içinde düzenleyebilirsiniz</li>
                                                <li>Sadece gerçek deneyimlerinizi paylaşın</li>
                                                <li>Hakaret ve küfür içeren yorumlar silinecektir</li>
                                                <li>Değerlendirmeniz hizmet sağlayıcının profilinde görüntülenecektir</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Butonlar -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Geri Dön
                                    </a>
                                    <button type="submit" class="btn review-submit-btn" id="submitBtn" disabled>
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Değerlendirmeyi Gönder
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.getElementById('ratingText');
    const submitBtn = document.getElementById('submitBtn');
    const commentInput = document.getElementById('comment');
    const imagesInput = document.getElementById('images');
    const photoPreview = document.getElementById('photoPreview');
    const previewContainer = document.getElementById('previewContainer');

    const ratingTexts = {
        1: 'Çok Kötü 😞',
        2: 'Kötü 😐',
        3: 'Orta 🙂',
        4: 'İyi 😊',
        5: 'Mükemmel 😍'
    };

    // Star rating functionality
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingInput.value = rating;
            ratingText.textContent = ratingTexts[rating];
            
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.innerHTML = '<i class="fas fa-star"></i>';
                    s.classList.add('active');
                } else {
                    s.innerHTML = '<i class="far fa-star"></i>';
                    s.classList.remove('active');
                }
            });
            
            checkFormValidity();
        });

        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.innerHTML = '<i class="fas fa-star"></i>';
                } else {
                    s.innerHTML = '<i class="far fa-star"></i>';
                }
            });
        });
    });

    document.getElementById('starRating').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        stars.forEach((s, index) => {
            if (index < currentRating) {
                s.innerHTML = '<i class="fas fa-star"></i>';
            } else {
                s.innerHTML = '<i class="far fa-star"></i>';
            }
        });
    });

    // Form validation
    function checkFormValidity() {
        const rating = ratingInput.value;
        const comment = commentInput.value.trim();
        
        if (rating && comment.length >= 10) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    commentInput.addEventListener('input', checkFormValidity);

    // Photo preview
    imagesInput.addEventListener('change', function() {
        const files = Array.from(this.files);
        previewContainer.innerHTML = '';
        
        if (files.length > 0) {
            photoPreview.style.display = 'block';
            
            files.forEach((file, index) => {
                if (index < 5) { // Maximum 5 photos
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';
                        previewItem.innerHTML = 
                            `<img src="${e.target.result}" alt="Preview ${index + 1}">
                             <div class="preview-name">${file.name}</div>`;
                        previewContainer.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            photoPreview.style.display = 'none';
        }
    });

    // Initial form state
    checkFormValidity();
});
</script>
@endsection 