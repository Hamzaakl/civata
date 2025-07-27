@extends('layouts.app')

@section('title', 'Randevu Al - {{ $service->title }}')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/booking-create.css') }}">
@endpush

@section('content')
<div class="booking-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Başlık -->
                <div class="text-center mb-4">
                    <h2 class="booking-title">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        Randevu Al
                    </h2>
                    <p class="text-muted">Hizmet sağlayıcıdan randevu almak için formu doldurun</p>
                </div>

                <div class="row">
                    <!-- Hizmet Bilgileri -->
                    <div class="col-md-4 mb-4">
                        <div class="service-info-card">
                            <div class="service-image">
                                @if($service->images && count(json_decode($service->images)) > 0)
                                    <img src="{{ asset('storage/' . json_decode($service->images)[0]) }}" 
                                         alt="{{ $service->title }}" class="img-fluid">
                                @else
                                    <div class="no-image">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="service-details">
                                <h5 class="service-title">{{ $service->title }}</h5>
                                <p class="service-category">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $service->serviceCategory->name }}
                                </p>
                                
                                <div class="provider-info">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="{{ $service->user->profile_photo ? asset('storage/' . $service->user->profile_photo) : 'https://via.placeholder.com/40' }}" 
                                             alt="{{ $service->user->name }}" class="provider-avatar me-2">
                                        <div>
                                            <strong>{{ $service->user->name }}</strong>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($service->user->rating >= $i)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif($service->user->rating > $i - 1)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                <small class="text-muted">({{ $service->user->total_reviews }})</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($service->price_min && $service->price_max)
                                    <div class="price-range">
                                        <i class="fas fa-lira-sign me-1"></i>
                                        {{ number_format($service->price_min) }} - {{ number_format($service->price_max) }} TL
                                        <small class="text-muted">({{ ucfirst($service->price_type) }})</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Rezervasyon Formu -->
                    <div class="col-md-8">
                        <div class="booking-form-card">
                            <form method="POST" action="{{ route('bookings.store', $service) }}">
                                @csrf

                                <!-- Açıklama -->
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="fas fa-clipboard-list text-primary me-2"></i>
                                        Ne tür bir hizmet istiyorsunuz?
                                    </label>
                                    <textarea class="form-control booking-input @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Lütfen ihtiyacınızı detaylı bir şekilde açıklayın..." 
                                              required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Adres -->
                                <div class="mb-4">
                                    <label for="address" class="form-label fw-semibold">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        Hizmet Adresi
                                    </label>
                                    <textarea class="form-control booking-input @error('address') is-invalid @enderror" 
                                              id="address" 
                                              name="address" 
                                              rows="3" 
                                              placeholder="Hizmetin yapılacağı tam adres..." 
                                              required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Tarih ve Saat -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="preferred_date" class="form-label fw-semibold">
                                            <i class="fas fa-calendar text-primary me-2"></i>
                                            Tercih Edilen Tarih
                                        </label>
                                        <input type="date" 
                                               class="form-control booking-input @error('preferred_date') is-invalid @enderror" 
                                               id="preferred_date" 
                                               name="preferred_date" 
                                               value="{{ old('preferred_date') }}" 
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                               required>
                                        @error('preferred_date')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="preferred_time" class="form-label fw-semibold">
                                            <i class="fas fa-clock text-primary me-2"></i>
                                            Tercih Edilen Saat <small class="text-muted">(Opsiyonel)</small>
                                        </label>
                                        <input type="time" 
                                               class="form-control booking-input @error('preferred_time') is-invalid @enderror" 
                                               id="preferred_time" 
                                               name="preferred_time" 
                                               value="{{ old('preferred_time') }}">
                                        @error('preferred_time')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Info Box -->
                                <div class="info-box mb-4">
                                    <div class="d-flex">
                                        <div class="info-icon">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div class="info-content">
                                            <strong>Rezervasyon Süreci:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Talebiniz hizmet sağlayıcıya iletilecektir</li>
                                                <li>Hizmet sağlayıcı fiyat teklifi ile birlikte cevap verecektir</li>
                                                <li>Onayladıktan sonra randevu kesinleşecektir</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Butonlar -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('services.show', $service) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Geri Dön
                                    </a>
                                    <button type="submit" class="btn booking-submit-btn">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Randevu Talebini Gönder
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
@endsection 