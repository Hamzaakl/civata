# 🔧 Civata - Ev Hizmetleri Platformu

**Civata**, ev hizmetleri için kapsamlı bir platform olarak tasarlanmıştır. Kamera montajından tesisat tamire, temizlikten teknik servise kadar tüm ev hizmetlerinde müşteriler ve hizmet sağlayıcıları bir araya getiren güvenilir bir çözümdür.

## 🚀 Özellikler

### 👥 Kullanıcı Sistemi

- **Üç tip kullanıcı**: Müşteri, Hizmet Sağlayıcı, Admin
- Kullanıcı profilleri (telefon, adres, şehir, bio, profil fotoğrafı)
- Doğrulama sistemi
- Puan ve değerlendirme sistemi

### 🏷️ Hizmet Kategorileri

- **Kamera Montaj** - Güvenlik kamerası kurulum ve montaj
- **Elektrik Tesisatı** - Elektrik arıza, priz montaj ve elektrik işleri
- **Su Tesisatı** - Su kaçağı, tesisat arıza ve su tesisatı işleri
- **Klima Montaj** - Klima kurulum, bakım ve tamir
- **Beyaz Eşya Tamiri** - Çamaşır makinesi, bulaşık makinesi, buzdolabı tamiri
- **Bilgisayar Tamiri** - Bilgisayar, laptop tamir ve teknik destek
- **Televizyon Tamiri** - LED, LCD, OLED televizyon tamir
- **Boyacı** - İç ve dış cephe boyama işleri

### 🛠️ Sistem Özellikleri

- **Hizmet İlanları**: Hizmet sağlayıcıları kendi hizmetlerini yayınlayabilir
- **Arama ve Filtreleme**: Kategori, şehir ve anahtar kelime ile arama
- **Rezervasyon Sistemi**: Müşteriler hizmet sağlayıcılardan randevu alabilir
- **Değerlendirme Sistemi**: 5 yıldızlı puan ve yorum sistemi
- **Mesajlaşma**: Kullanıcılar arası güvenli iletişim
- **Fiyat Seçenekleri**: Sabit fiyat, saatlik ücret veya pazarlıklı

## 🏗️ Teknik Yapı

### Backend

- **Framework**: Laravel 8
- **Veritabanı**: MySQL
- **Authentication**: Laravel Sanctum
- **ORM**: Eloquent

### Frontend

- **Template Engine**: Blade
- **CSS Framework**: Bootstrap 5
- **Icons**: Font Awesome 6
- **Responsive Design**: Mobile-first approach

## 📊 Veritabanı Yapısı

### Tablolar

1. **users** - Kullanıcı bilgileri ve roller
2. **service_categories** - Hizmet kategorileri
3. **services** - Hizmet ilanları
4. **bookings** - Rezervasyonlar
5. **reviews** - Değerlendirmeler ve yorumlar
6. **messages** - Kullanıcı mesajları

### Temel İlişkiler

```
User (1:N) → Services
User (1:N) → Bookings (Customer)
User (1:N) → Bookings (Provider)
Service (1:N) → Bookings
Booking (1:N) → Reviews
User (1:N) → Messages (Sender/Recipient)
```

## 🛠️ Kurulum

### Gereksinimler

- PHP >= 7.3
- Composer
- MySQL
- Node.js (opsiyonel, frontend geliştirme için)

### Kurulum Adımları

1. **Projeyi klonlayın**

   ```bash
   git clone <repository-url>
   cd civata
   ```

2. **Bağımlılıkları yükleyin**

   ```bash
   composer install
   ```

3. **Ortam dosyasını hazırlayın**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Veritabanı ayarlarını yapın**
   `.env` dosyasında veritabanı bilgilerini güncelleyin:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=civata
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Veritabanını oluşturun ve migrationları çalıştırın**

   ```bash
   php artisan migrate
   ```

6. **Örnek verileri yükleyin**

   ```bash
   php artisan db:seed
   ```

7. **Sunucuyu başlatın**
   ```bash
   php artisan serve
   ```

Proje `http://localhost:8000` adresinde çalışacaktır.

## 👤 Örnek Kullanıcılar

### Admin

- **Email**: admin@civata.com
- **Şifre**: password

### Hizmet Sağlayıcıları

- **Ahmet Kaya (Elektrikçi)**: ahmet@example.com / password
- **Mehmet Öz (Tesisatçı)**: mehmet@example.com / password
- **Ali Yılmaz (Kamera Uzmanı)**: ali@example.com / password
- **Fatma Demir (Temizlik)**: fatma@example.com / password

### Müşteriler

- **Zeynep Özkan**: zeynep@example.com / password
- **Can Demir**: can@example.com / password

## 📱 Sayfa Yapısı

### Ana Sayfa (`/`)

- Hero bölümü ve arama formu
- Hizmet kategorileri
- Öne çıkan hizmetler
- En iyi hizmet sağlayıcılar
- "Nasıl çalışır?" bölümü

### Hizmetler (`/services`)

- Tüm hizmetlerin listelenmesi
- Arama ve filtreleme özellikleri
- Sayfalama

### Hizmet Detayı (`/services/{id}`)

- Hizmet detay sayfası
- Hizmet sağlayıcı bilgileri
- Benzer hizmetler

### Kategori Sayfası (`/category/{slug}`)

- Kategoriye özel hizmet listesi

## 🎨 Tasarım Özellikleri

- **Modern ve temiz arayüz**
- **Responsive tasarım** (mobil uyumlu)
- **Gradient renkler** ve **hover efektleri**
- **Card-based layout**
- **Puan gösterimi** (yıldız sistemi)
- **İkon tabanlı kategoriler**

## 🔄 Geliştirilmesi Planlanan Özellikler

- [ ] Kullanıcı kimlik doğrulama sistemi
- [ ] Hizmet sağlayıcı paneli
- [ ] Rezervasyon yönetimi
- [ ] Mesajlaşma sistemi
- [ ] Ödeme entegrasyonu
- [ ] Bildirim sistemi
- [ ] Mobil uygulama API'leri
- [ ] Gelişmiş arama filtreleri
- [ ] Harita entegrasyonu
- [ ] Dosya yükleme sistemi

## 🤝 Katkıda Bulunma

1. Bu repoyu fork edin
2. Feature branch oluşturun (`git checkout -b feature/AmazingFeature`)
3. Değişikliklerinizi commit edin (`git commit -m 'Add some AmazingFeature'`)
4. Branch'inizi push edin (`git push origin feature/AmazingFeature`)
5. Pull Request oluşturun

**Not**: Bu proje aktif geliştirme aşamasındadır. Yeni özellikler düzenli olarak eklenmektedir.
