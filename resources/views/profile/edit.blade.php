@extends('layouts.app')

@section('title', 'Profil Düzenle')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endpush

@section('content')
<div class="profile-edit-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Başlık -->
                <div class="page-header mb-4">
                    <h2 class="page-title">
                        <i class="fas fa-user-edit text-primary me-2"></i>
                        Profil Düzenle
                    </h2>
                    <p class="text-muted">Hesap bilgilerinizi ve profil ayarlarınızı güncelleyin</p>
                </div>

                <!-- Profil Bilgileri Formu -->
                <div class="profile-form-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-user text-primary me-2"></i>
                            Profil Bilgileri
                        </h5>
                    </div>
                    
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Profil Fotoğrafı -->
                        <div class="photo-section mb-4">
                            <label class="form-label fw-semibold">Profil Fotoğrafı</label>
                            <div class="photo-upload-area">
                                <div class="current-photo">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                             alt="Profil Fotoğrafı" class="profile-preview" id="photoPreview">
                                    @else
                                        <div class="no-photo-placeholder" id="photoPreview">
                                            <i class="fas fa-user fa-3x"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="photo-controls">
                                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="display: none;">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('profile_photo').click()">
                                        <i class="fas fa-camera me-1"></i>
                                        Fotoğraf Seç
                                    </button>
                                    
                                    @if($user->profile_photo)
                                        <form method="POST" action="{{ route('profile.delete-photo') }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Profil fotoğrafını silmek istediğinizden emin misiniz?')">
                                                <i class="fas fa-trash me-1"></i>
                                                Sil
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @error('profile_photo')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Temel Bilgiler -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-user text-primary me-1"></i>
                                    Ad Soyad
                                </label>
                                <input type="text" 
                                       class="form-control profile-input @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope text-primary me-1"></i>
                                    E-posta
                                </label>
                                <input type="email" 
                                       class="form-control profile-input @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="fas fa-phone text-primary me-1"></i>
                                    Telefon
                                </label>
                                <input type="text" 
                                       class="form-control profile-input @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}" 
                                       placeholder="05XX XXX XX XX">
                                @error('phone')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                    Şehir
                                </label>
                                <input type="text" 
                                       class="form-control profile-input @error('city') is-invalid @enderror" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city', $user->city) }}" 
                                       placeholder="İstanbul">
                                @error('city')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Adres -->
                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">
                                <i class="fas fa-home text-primary me-1"></i>
                                Adres
                            </label>
                            <textarea class="form-control profile-input @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Tam adresinizi yazın...">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Bio (Hizmet sağlayıcılar için) -->
                        @if($user->isServiceProvider())
                            <div class="mb-4">
                                <label for="bio" class="form-label fw-semibold">
                                    <i class="fas fa-user-alt text-primary me-1"></i>
                                    Hakkınızda
                                </label>
                                <textarea class="form-control profile-input @error('bio') is-invalid @enderror" 
                                          id="bio" 
                                          name="bio" 
                                          rows="4" 
                                          placeholder="Kendinizi ve hizmetlerinizi tanıtın...">{{ old('bio', $user->bio) }}</textarea>
                                <small class="form-text text-muted">Bu bilgi profil sayfanızda görüntülenecektir.</small>
                                @error('bio')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif

                        <!-- Kullanıcı Tipi (Sadece gösterim) -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tag text-primary me-1"></i>
                                Hesap Türü
                            </label>
                            <div class="user-type-display">
                                <span class="user-type-badge user-type-{{ $user->user_type }}">
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
                                </span>
                                <small class="text-muted ms-2">Hesap türünüz değiştirilemez</small>
                            </div>
                        </div>

                        <!-- Kaydet Butonu -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Geri Dön
                            </a>
                            <button type="submit" class="btn profile-save-btn">
                                <i class="fas fa-save me-2"></i>
                                Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Şifre Değiştirme -->
                <div class="password-form-card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-lock text-primary me-2"></i>
                            Şifre Değiştir
                        </h5>
                    </div>
                    
                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">
                                <i class="fas fa-key text-primary me-1"></i>
                                Mevcut Şifre
                            </label>
                            <input type="password" 
                                   class="form-control profile-input @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock text-primary me-1"></i>
                                    Yeni Şifre
                                </label>
                                <input type="password" 
                                       class="form-control profile-input @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="fas fa-lock text-primary me-1"></i>
                                    Yeni Şifre (Tekrar)
                                </label>
                                <input type="password" 
                                       class="form-control profile-input" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>
                                Şifreyi Değiştir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Profil fotoğrafı önizleme
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Profil Fotoğrafı" class="profile-preview">';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection 