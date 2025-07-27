@extends('layouts.app')

@section('title', 'Kayıt Ol - Civata')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-register.css') }}">
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-9">
                <div class="auth-card">
                    <div class="card-body p-4">
                        <!-- Logo ve Başlık -->
                        <div class="text-center mb-3">
                            <div class="auth-logo small mb-2">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">Civata'ya Katılın</h3>
                            <p class="text-muted small">Hesabınızı oluşturun ve hemen başlayın</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Ad Soyad -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold text-dark small">
                                    <i class="fas fa-user me-1 text-primary"></i>Ad Soyad
                                </label>
                                <input id="name" 
                                       type="text" 
                                       class="form-control auth-input @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autocomplete="name" 
                                       autofocus
                                       placeholder="Ad Soyad">
                                @error('name')
                                    <div class="invalid-feedback small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-dark small">
                                    <i class="fas fa-envelope me-1 text-primary"></i>E-posta Adresi
                                </label>
                                <input id="email" 
                                       type="email" 
                                       class="form-control auth-input @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email"
                                       placeholder="ornek@email.com">
                                @error('email')
                                    <div class="invalid-feedback small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Kullanıcı Tipi Seçimi -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small mb-2">
                                    <i class="fas fa-users me-1 text-primary"></i>Hesap Tipi
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="user-type-card" onclick="selectUserType('customer')">
                                            <input type="radio" name="user_type" value="customer" id="customer" class="d-none" checked>
                                            <i class="fas fa-user-circle text-primary mb-1"></i>
                                            <h6 class="fw-bold mb-0 small">Müşteri</h6>
                                            <small class="text-muted" style="font-size: 11px;">Hizmet almak istiyorum</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="user-type-card" onclick="selectUserType('service_provider')">
                                            <input type="radio" name="user_type" value="service_provider" id="service_provider" class="d-none">
                                            <i class="fas fa-tools text-primary mb-1"></i>
                                            <h6 class="fw-bold mb-0 small">Hizmet Sağlayıcı</h6>
                                            <small class="text-muted" style="font-size: 11px;">Hizmet vermek istiyorum</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Şifre -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold text-dark small">
                                    <i class="fas fa-lock me-1 text-primary"></i>Şifre
                                </label>
                                <input id="password" 
                                       type="password" 
                                       class="form-control auth-input @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="••••••••">
                                @error('password')
                                    <div class="invalid-feedback small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Şifre Tekrar -->
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label fw-semibold text-dark small">
                                    <i class="fas fa-lock me-1 text-primary"></i>Şifre Tekrar
                                </label>
                                <input id="password-confirm" 
                                       type="password" 
                                       class="form-control auth-input" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="••••••••">
                            </div>

                            <!-- Kullanım Şartları -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label text-muted small" for="terms">
                                        <a href="#" class="text-primary text-decoration-none">Kullanım şartlarını</a> ve 
                                        <a href="#" class="text-primary text-decoration-none">gizlilik politikasını</a> kabul ediyorum
                                    </label>
                                </div>
                            </div>

                            <!-- Register Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn auth-btn text-white fw-bold">
                                    <i class="fas fa-user-plus me-2"></i>Hesap Oluştur
                                </button>
                            </div>
                        </form>

                        <!-- Login Link -->
                        <div class="auth-footer text-center">
                            <p class="mb-2 text-muted small">Zaten hesabınız var mı?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-1"></i>Giriş Yap
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Güvenlik Bilgisi -->
                <div class="text-center mt-3">
                    <small class="text-white-75">
                        <i class="fas fa-shield-alt me-1"></i>
                        Verileriniz SSL ile korunmaktadır
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
function selectUserType(type) {
    // Tüm kartları temizle
    document.querySelectorAll('.user-type-card').forEach(card => {
        card.classList.remove('active');
    });
    
    // Seçilen kartı aktif yap
    event.currentTarget.classList.add('active');
    
    // Radio button'u seç
    document.getElementById(type).checked = true;
}

// Sayfa yüklendiğinde customer kartını aktif yap
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.user-type-card').classList.add('active');
});
</script>
@endsection
