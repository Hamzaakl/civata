@extends('layouts.app')

@section('title', 'Yeni Hizmet Ekle')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/provider-service-form.css') }}">
@endpush

@section('content')
<div class="service-form-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Başlık -->
                <div class="form-header mb-4">
                    <h2 class="form-title">
                        <i class="fas fa-plus text-success me-2"></i>
                        Yeni Hizmet Ekle
                    </h2>
                    <p class="text-muted">Hizmetinizi tanıtın ve müşterilere ulaşın</p>
                </div>

                <form method="POST" action="{{ route('provider.services.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Sol Kolon - Ana Bilgiler -->
                        <div class="col-lg-8">
                            <div class="form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        Temel Bilgiler
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Başlık -->
                                    <div class="mb-4">
                                        <label for="title" class="form-label fw-semibold">
                                            <i class="fas fa-heading text-primary me-2"></i>
                                            Hizmet Başlığı *
                                        </label>
                                        <input type="text" 
                                               class="form-control service-input @error('title') is-invalid @enderror" 
                                               id="title" 
                                               name="title" 
                                               value="{{ old('title') }}"
                                               placeholder="Örn: Profesyonel Kamera Montajı"
                                               required>
                                        @error('title')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Kategori -->
                                    <div class="mb-4">
                                        <label for="service_category_id" class="form-label fw-semibold">
                                            <i class="fas fa-list text-primary me-2"></i>
                                            Hizmet Kategorisi *
                                        </label>
                                        <select class="form-select service-input @error('service_category_id') is-invalid @enderror" 
                                                id="service_category_id" 
                                                name="service_category_id" 
                                                required>
                                            <option value="">Kategori seçin...</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                        {{ old('service_category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_category_id')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Açıklama -->
                                    <div class="mb-4">
                                        <label for="description" class="form-label fw-semibold">
                                            <i class="fas fa-align-left text-primary me-2"></i>
                                            Hizmet Açıklaması *
                                        </label>
                                        <textarea class="form-control service-input @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="6" 
                                                  placeholder="Hizmetinizi detaylı bir şekilde açıklayın. Deneyiminiz, kullandığınız ekipmanlar, hizmet süreciniz vb."
                                                  required>{{ old('description') }}</textarea>
                                        <small class="form-text text-muted">Minimum 50 karakter giriniz.</small>
                                        @error('description')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Hizmet Alanı -->
                                    <div class="mb-4">
                                        <label for="service_area" class="form-label fw-semibold">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            Hizmet Alanı *
                                        </label>
                                        <input type="text" 
                                               class="form-control service-input @error('service_area') is-invalid @enderror" 
                                               id="service_area" 
                                               name="service_area" 
                                               value="{{ old('service_area') }}"
                                               placeholder="Örn: İstanbul Avrupa Yakası, Ankara Çankaya"
                                               required>
                                        <small class="form-text text-muted">Hizmet verdiğiniz bölgeleri belirtin</small>
                                        @error('service_area')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Fiyatlandırma -->
                            <div class="form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-lira-sign text-success me-2"></i>
                                        Fiyatlandırma
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Fiyat Tipi -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-tags text-success me-2"></i>
                                            Fiyat Türü *
                                        </label>
                                        <div class="price-type-options">
                                            <div class="form-check price-option">
                                                <input class="form-check-input" type="radio" name="price_type" id="fixed" value="fixed" {{ old('price_type') == 'fixed' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="fixed">
                                                    <i class="fas fa-tag me-2"></i>
                                                    <strong>Sabit Fiyat</strong>
                                                    <small class="d-block text-muted">Belirli bir fiyat aralığı</small>
                                                </label>
                                            </div>
                                            <div class="form-check price-option">
                                                <input class="form-check-input" type="radio" name="price_type" id="hourly" value="hourly" {{ old('price_type') == 'hourly' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="hourly">
                                                    <i class="fas fa-clock me-2"></i>
                                                    <strong>Saatlik Ücret</strong>
                                                    <small class="d-block text-muted">Saat başına fiyatlandırma</small>
                                                </label>
                                            </div>
                                            <div class="form-check price-option">
                                                <input class="form-check-input" type="radio" name="price_type" id="negotiable" value="negotiable" {{ old('price_type') == 'negotiable' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="negotiable">
                                                    <i class="fas fa-handshake me-2"></i>
                                                    <strong>Pazarlıklı</strong>
                                                    <small class="d-block text-muted">Müşteri ile anlaşmaya göre</small>
                                                </label>
                                            </div>
                                        </div>
                                        @error('price_type')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Fiyat Aralığı -->
                                    <div class="price-range" id="priceRange" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="price_min" class="form-label">Minimum Fiyat (TL)</label>
                                                <input type="number" 
                                                       class="form-control service-input @error('price_min') is-invalid @enderror" 
                                                       id="price_min" 
                                                       name="price_min" 
                                                       value="{{ old('price_min') }}"
                                                       min="0" 
                                                       step="0.01">
                                                @error('price_min')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="price_max" class="form-label">Maksimum Fiyat (TL)</label>
                                                <input type="number" 
                                                       class="form-control service-input @error('price_max') is-invalid @enderror" 
                                                       id="price_max" 
                                                       name="price_max" 
                                                       value="{{ old('price_max') }}"
                                                       min="0" 
                                                       step="0.01">
                                                @error('price_max')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sağ Kolon - Fotoğraflar ve Ayarlar -->
                        <div class="col-lg-4">
                            <!-- Fotoğraf Yükleme -->
                            <div class="form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-camera text-info me-2"></i>
                                        Hizmet Fotoğrafları
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="photo-upload-section">
                                        <input type="file" 
                                               class="form-control @error('images.*') is-invalid @enderror" 
                                               id="images" 
                                               name="images[]" 
                                               multiple 
                                               accept="image/*">
                                        <small class="form-text text-muted">
                                            JPG, PNG, GIF formatında maksimum 5 fotoğraf yükleyebilirsiniz.
                                        </small>
                                        @error('images.*')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Fotoğraf Önizleme -->
                                    <div class="photo-preview mt-3" id="photoPreview" style="display: none;">
                                        <label class="form-label fw-semibold">Seçilen Fotoğraflar:</label>
                                        <div class="preview-grid" id="previewGrid"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hizmet Ayarları -->
                            <div class="form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-cogs text-warning me-2"></i>
                                        Hizmet Ayarları
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            <i class="fas fa-star text-warning me-1"></i>
                                            <strong>Öne Çıkan Hizmet</strong>
                                            <small class="d-block text-muted">Hizmetiniz daha fazla görüntülenecek</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Bilgi Kutusu -->
                            <div class="info-card">
                                <div class="info-header">
                                    <i class="fas fa-lightbulb text-warning me-2"></i>
                                    <strong>İpuçları</strong>
                                </div>
                                <ul class="info-list">
                                    <li>Detaylı açıklama yazın</li>
                                    <li>Kaliteli fotoğraflar kullanın</li>
                                    <li>Gerçekçi fiyat belirleyin</li>
                                    <li>Hizmet alanınızı net belirtin</li>
                                    <li>İletişim bilgilerinizi güncel tutun</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Butonları -->
                    <div class="form-actions">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('provider.services') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Geri Dön
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>
                                Hizmeti Kaydet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceTypeRadios = document.querySelectorAll('input[name="price_type"]');
    const priceRange = document.getElementById('priceRange');
    const imagesInput = document.getElementById('images');
    const photoPreview = document.getElementById('photoPreview');
    const previewGrid = document.getElementById('previewGrid');

    // Fiyat türü değişikliği
    priceTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'fixed' || this.value === 'hourly') {
                priceRange.style.display = 'block';
            } else {
                priceRange.style.display = 'none';
            }
        });
    });

    // Sayfa yüklendiğinde seçili durumu kontrol et
    const selectedPriceType = document.querySelector('input[name="price_type"]:checked');
    if (selectedPriceType && (selectedPriceType.value === 'fixed' || selectedPriceType.value === 'hourly')) {
        priceRange.style.display = 'block';
    }

    // Fotoğraf önizleme
    imagesInput.addEventListener('change', function() {
        const files = Array.from(this.files);
        previewGrid.innerHTML = '';
        
        if (files.length > 0) {
            photoPreview.style.display = 'block';
            
            files.forEach((file, index) => {
                if (index < 5) { // Maksimum 5 fotoğraf
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';
                        previewItem.innerHTML = 
                            `<img src="${e.target.result}" alt="Preview ${index + 1}">
                             <div class="preview-name">${file.name}</div>`;
                        previewGrid.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            photoPreview.style.display = 'none';
        }
    });
});
</script>
@endsection 