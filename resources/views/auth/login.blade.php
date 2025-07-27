@extends('layouts.app')

@section('title', 'Giriş Yap - Civata')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-login.css') }}">
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="auth-card">
                    <div class="card-body p-4">
                        <!-- Logo ve Başlık -->
                        <div class="text-center mb-4">
                            <div class="auth-logo mb-3">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">Civata'ya Hoş Geldiniz</h3>
                            <p class="text-muted small">Hesabınıza giriş yapın</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Input -->
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
                                       autofocus
                                       placeholder="ornek@email.com">
                                @error('email')
                                    <div class="invalid-feedback small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold text-dark small">
                                    <i class="fas fa-lock me-1 text-primary"></i>Şifre
                                </label>
                                <input id="password" 
                                       type="password" 
                                       class="form-control auth-input @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="current-password"
                                       placeholder="••••••••">
                                @error('password')
                                    <div class="invalid-feedback small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="remember" 
                                           id="remember" 
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted small" for="remember">
                                        Beni hatırla
                                    </label>
                                </div>
                                
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary small fw-semibold">
                                        Şifremi unuttum
                                    </a>
                                @endif
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn auth-btn text-white fw-bold">
                                    <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                                </button>
                            </div>
                        </form>

                        <!-- Register Link -->
                        <div class="auth-footer text-center">
                            <p class="mb-2 text-muted small">Henüz hesabınız yok mu?</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-1"></i>Hemen Kayıt Ol
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

@endsection
