# 🔧 Civata - Ev Hizmetleri Platformu

**Civata**, ev hizmetleri için kapsamlı bir platform olarak tasarlanmıştır. Kamera montajından tesisat tamire, temizlikten teknik servise kadar tüm ev hizmetlerinde müşteriler ve hizmet sağlayıcıları bir araya getiren güvenilir bir çözümdür.

## 🚀 Özellikler

### �� Kullanıcı Sistemi

- ✅ **Üç tip kullanıcı**: Müşteri, Hizmet Sağlayıcı, Admin
- ✅ Kullanıcı profilleri (telefon, adres, şehir, bio, profil fotoğrafı)
- ✅ Laravel UI Authentication sistemi
- ✅ Kullanıcı rolleri ve yetkilendirme
- 🔄 Puan ve değerlendirme sistemi

### 🏷️ Hizmet Kategorileri

- ✅ **Kamera Montaj** - Güvenlik kamerası kurulum ve montaj
- ✅ **Elektrik Tesisatı** - Elektrik arıza, priz montaj ve elektrik işleri
- ✅ **Su Tesisatı** - Su kaçağı, tesisat arıza ve su tesisatı işleri
- ✅ **Klima Montaj** - Klima kurulum, bakım ve tamir
- ✅ **Beyaz Eşya Tamiri** - Çamaşır makinesi, bulaşık makinesi, buzdolabı tamiri
- ✅ **Bilgisayar Tamiri** - Bilgisayar, laptop tamir ve teknik destek
- ✅ **Televizyon Tamiri** - LED, LCD, OLED televizyon tamir
- ✅ **Boyacı** - İç ve dış cephe boyama işleri

### 🛠️ Sistem Özellikleri

- ✅ **Hizmet İlanları**: Hizmet sağlayıcıları kendi hizmetlerini yayınlayabilir
- ✅ **Arama ve Filtreleme**: Kategori, şehir ve anahtar kelime ile arama
- ✅ **Sayfa Yapısı**: Ana sayfa, hizmet listesi, hizmet detay, kategori sayfaları
- ✅ **Rezervasyon Sistemi**: Tam fonksiyonel randevu alma, kabul/red, durum takibi
- 🔄 **Değerlendirme Sistemi**: 5 yıldızlı puan ve yorum sistemi
- 🔄 **Mesajlaşma**: Kullanıcılar arası güvenli iletişim
- ✅ **Fiyat Seçenekleri**: Sabit fiyat, saatlik ücret veya pazarlıklı

## 🏗️ Teknik Yapı

### Backend

- **Framework**: Laravel 8
- **Veritabanı**: MySQL
- **Authentication**: Laravel UI + Bootstrap Auth
- **ORM**: Eloquent
- **Seeders**: Kategori, kullanıcı ve hizmet verileri

### Frontend

- **Template Engine**: Blade
- **CSS Framework**: Bootstrap 5
- **Icons**: Font Awesome 6
- **CSS Yapısı**: Modüler CSS (sayfa bazında ayrı dosyalar)
- **Responsive Design**: Mobile-first approach
- **Auth Pages**: Modern ve profesyonel tasarım

### 🎨 CSS Mimarisi

```
resources/css/                    # Kaynak CSS dosyaları
├── auth-login.css               # Login sayfası stilleri
├── auth-register.css            # Kayıt sayfası stilleri
├── auth-forgot-password.css     # Şifremi unuttum stilleri
├── booking-create.css           # Randevu alma formu stilleri
├── booking-show.css             # Rezervasyon detay stilleri
├── booking-index.css            # Rezervasyon listesi stilleri
├── profile-show.css             # Profil görüntüleme stilleri
├── profile-edit.css             # Profil düzenleme stilleri
├── review-create.css            # Değerlendirme oluşturma stilleri
├── review-show.css              # Değerlendirme görüntüleme stilleri
├── provider-dashboard.css       # Hizmet sağlayıcı dashboard stilleri
├── provider-services.css        # Hizmet yönetimi stilleri
└── admin-dashboard.css          # Admin panel dashboard stilleri

public/css/                      # Sunulan CSS dosyaları
├── app.css                      # Bootstrap + Global stiller
├── auth-login.css               # Compile edilmiş login CSS
├── auth-register.css            # Compile edilmiş register CSS
├── auth-forgot-password.css     # Compile edilmiş forgot password CSS
├── booking-create.css           # Compile edilmiş booking create CSS
├── booking-show.css             # Compile edilmiş booking show CSS
├── booking-index.css            # Compile edilmiş booking index CSS
├── profile-show.css             # Compile edilmiş profile show CSS
├── profile-edit.css             # Compile edilmiş profile edit CSS
├── review-create.css            # Compile edilmiş review create CSS
├── review-show.css              # Compile edilmiş review show CSS
├── provider-dashboard.css       # Compile edilmiş provider dashboard CSS
├── provider-services.css        # Compile edilmiş provider services CSS
└── admin-dashboard.css          # Compile edilmiş admin dashboard CSS
```

### 📱 Sayfa Yapısı

- **Modüler CSS**: Her sayfa kendi CSS dosyasına sahip
- **@push('styles')**: Dinamik CSS yükleme sistemi
- **Asset Pipeline**: Laravel Mix ile SASS compile
- **Performance**: Sadece gerekli CSS dosyaları yüklenir

## 📊 Veritabanı Yapısı

### Tablolar

1. ✅ **users** - Kullanıcı bilgileri ve roller
2. ✅ **service_categories** - Hizmet kategorileri
3. ✅ **services** - Hizmet ilanları
4. ✅ **bookings** - Rezervasyonlar
5. ✅ **reviews** - Değerlendirmeler ve yorumlar
6. ✅ **messages** - Kullanıcı mesajları

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
- Node.js (frontend geliştirme için)

### Kurulum Adımları

1. **Projeyi klonlayın**

   ```bash
   git clone <repository-url>
   cd civata
   ```

2. **Bağımlılıkları yükleyin**

   ```bash
   composer install
   npm install
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

7. **Frontend assets'leri compile edin**

   ```bash
   npm run dev
   ```

8. **Sunucuyu başlatın**
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

- ✅ Hero bölümü ve arama formu
- ✅ Hizmet kategorileri
- ✅ Öne çıkan hizmetler
- ✅ En iyi hizmet sağlayıcılar
- ✅ "Nasıl çalışır?" bölümü

### Hizmetler (`/services`)

- ✅ Tüm hizmetlerin listelenmesi
- ✅ Arama ve filtreleme özellikleri
- ✅ Sayfalama

### Hizmet Detayı (`/services/{id}`)

- ✅ Hizmet detay sayfası
- ✅ Hizmet sağlayıcı bilgileri
- ✅ Benzer hizmetler

### Kategori Sayfası (`/category/{slug}`)

- ✅ Kategoriye özel hizmet listesi

### Authentication Sayfaları

- ✅ **Login (`/login`)**: Modern ve profesyonel tasarım
- ✅ **Register (`/register`)**: Kullanıcı tipi seçimi ile kayıt
- ✅ **Forgot Password (`/password/reset`)**: Şifre sıfırlama

## 🎨 Tasarım Özellikleri

### Genel Tasarım

- ✅ **Modern ve temiz arayüz**
- ✅ **Responsive tasarım** (mobil uyumlu)
- ✅ **Gradient renkler** ve **hover efektleri**
- ✅ **Card-based layout**
- ✅ **Puan gösterimi** (yıldız sistemi)
- ✅ **İkon tabanlı kategoriler**

### Auth Sayfaları Özellikleri

- ✅ **Glassmorphism Effect**: Şeffaf arka plan + blur efekti
- ✅ **Gradient Backgrounds**: Modern renk geçişleri
- ✅ **Smooth Animations**: Hover ve focus animasyonları
- ✅ **Responsive Grid**: Mobil ve desktop uyumlu
- ✅ **Form Validation**: Türkçe hata mesajları
- ✅ **Interactive Elements**: User type seçimi kartları

## 🔄 Geliştirme Süreci

### ✅ Tamamlanan Özellikler

- [x] Laravel projesi kurulumu
- [x] Veritabanı tasarımı ve migrationları
- [x] Model ilişkileri ve seeders
- [x] Ana sayfa tasarımı ve işlevselliği
- [x] Hizmet listeleme ve detay sayfaları
- [x] Kategori sayfaları
- [x] Laravel UI authentication entegrasyonu
- [x] Modern auth sayfaları tasarımı
- [x] Modüler CSS yapısı implementasyonu
- [x] Responsive tasarım optimizasyonu
- [x] Rezervasyon sistemi (randevu alma, durum takibi, fiyat teklifi)
- [x] Navigation bar rezervasyon linki entegrasyonu
- [x] Kullanıcı profil yönetimi (görüntüleme, düzenleme, fotoğraf yükleme)
- [x] Profil sayfaları ve kullanıcı profillerine linkler
- [x] Değerlendirme ve yorum sistemi (5 yıldız puan, fotoğraf yükleme, otomatik rating hesaplama)
- [x] Hizmet sağlayıcı paneli (dashboard, hizmet yönetimi, istatistikler)
- [x] Admin paneli (dashboard, kullanıcı yönetimi, kategori yönetimi, platform istatistikleri)

### 🔄 Devam Eden Özellikler

- [ ] Gerçek zamanlı mesajlaşma sistemi

### 📋 Geliştirilmesi Planlanan Özellikler

- [ ] Değerlendirme ve yorum sistemi
- [ ] Ödeme entegrasyonu
- [ ] Bildirim sistemi
- [ ] Mobil uygulama API'leri
- [ ] Gelişmiş arama filtreleri
- [ ] Harita entegrasyonu
- [ ] Dosya yükleme sistemi

## 🚀 Performans Özellikleri

### CSS Optimizasyonu

- **Modüler Yükleme**: Her sayfa sadece kendi CSS'ini yükler
- **Küçük Dosya Boyutları**: Auth CSS'leri 1.6-2.2KB arası
- **Cache Friendly**: Asset versioning ile cache management
- **Development Workflow**: npm run dev/watch/production

### Database Optimizasyonu

- **Eloquent Relations**: Efficient data fetching
- **Database Indexing**: Primary ve foreign key indexleri
- **Pagination**: Büyük veri setleri için sayfalama
- **Seeded Data**: Test için hazır örnek veriler

## 🤝 Katkıda Bulunma

1. Bu repoyu fork edin
2. Feature branch oluşturun (`git checkout -b feature/AmazingFeature`)
3. Değişikliklerinizi commit edin (`git commit -m 'Add some AmazingFeature'`)
4. Branch'inizi push edin (`git push origin feature/AmazingFeature`)
5. Pull Request oluşturun

## 📝 Geliştirici Notları

### CSS Geliştirme

```bash
# Yeni sayfa CSS'i oluşturmak için:
1. resources/css/yeni-sayfa.css oluştur
2. public/css/yeni-sayfa.css kopyala
3. Blade'de @push('styles') ile include et
```

### Database Değişiklikleri

```bash
php artisan make:migration create_new_table
php artisan migrate
php artisan db:seed --class=NewTableSeeder
```

### Asset Compilation

```bash
npm run dev        # Development build
npm run watch      # Watch for changes
npm run production # Production build (minified)
```

---

**Not**: Bu proje aktif geliştirme aşamasındadır. Yeni özellikler düzenli olarak eklenmektedir.
