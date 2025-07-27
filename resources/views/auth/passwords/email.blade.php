@extends('layouts.app')

@section('title', 'Şifremi Unuttum - Civata')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-forgot-password.css') }}">
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
                                <i class="fas fa-key"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">Şifrenizi mi Unuttunuz?</h3>
                            <p class="text-muted small">E-posta adresinizi girin, size şifre sıfırlama bağlantısı gönderelim</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success rounded-3 mb-3" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
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

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn auth-btn text-white fw-bold">
                                    <i class="fas fa-paper-plane me-2"></i>Sıfırlama Bağlantısı Gönder
                                </button>
                            </div>
                        </form>

                        <!-- Back to Login -->
                        <div class="auth-footer text-center">
                            <p class="mb-2 text-muted small">Şifrenizi hatırladınız mı?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i>Giriş Sayfasına Dön
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
