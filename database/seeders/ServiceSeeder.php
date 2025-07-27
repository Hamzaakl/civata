<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'user_id' => User::where('email', 'ahmet@example.com')->first()->id,
                'service_category_id' => ServiceCategory::where('slug', 'elektrik-tesisati')->first()->id,
                'title' => 'Elektrik Arıza ve Priz Montajı',
                'description' => 'Evinizdeki tüm elektrik arızalarını giderir, yeni priz ve anahtar montajı yaparım. 15 yıllık tecrübem ile güvenli ve kaliteli iş garantisi veriyorum.',
                'price_min' => 100,
                'price_max' => 500,
                'price_type' => 'negotiable',
                'service_area' => 'İstanbul Avrupa Yakası',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'user_id' => User::where('email', 'mehmet@example.com')->first()->id,
                'service_category_id' => ServiceCategory::where('slug', 'su-tesisati')->first()->id,
                'title' => 'Su Kaçağı Tespiti ve Tamir',
                'description' => 'Su kaçağı tespiti, tesisat tamir ve yenileme işleri. 7/24 acil servis hizmeti veriyorum. Modern cihazlarla kesin tespit.',
                'price_min' => 150,
                'price_max' => 800,
                'price_type' => 'hourly',
                'service_area' => 'İstanbul - Tüm bölgeler',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'user_id' => User::where('email', 'ali@example.com')->first()->id,
                'service_category_id' => ServiceCategory::where('slug', 'kamera-montaj')->first()->id,
                'title' => 'Güvenlik Kamerası Kurulum',
                'description' => 'Ev ve iş yerleriniz için profesyonel güvenlik kamerası kurulumu. IP kamera, analog kamera ve NVR sistemleri.',
                'price_min' => 500,
                'price_max' => 2500,
                'price_type' => 'fixed',
                'service_area' => 'Ankara ve çevre iller',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'user_id' => User::where('email', 'ahmet@example.com')->first()->id,
                'service_category_id' => ServiceCategory::where('slug', 'elektrik-tesisati')->first()->id,
                'title' => 'Şalt Montajı ve Elektrik Panosu',
                'description' => 'Elektrik panosu montajı, şalt işleri ve elektrik tesisatı yenileme. Ruhsatlı elektrikçi hizmeti.',
                'price_min' => 300,
                'price_max' => 1500,
                'price_type' => 'negotiable',
                'service_area' => 'İstanbul',
                'is_active' => true,
            ],
            [
                'user_id' => User::where('email', 'fatma@example.com')->first()->id,
                'service_category_id' => ServiceCategory::where('slug', 'beyaz-esya-tamiri')->first()->id,
                'title' => 'Ev Temizliği ve Bakım',
                'description' => 'Detaylı ev temizliği, cam silme ve genel bakım hizmetleri. Kendi malzemelerimle çalışırım.',
                'price_min' => 80,
                'price_max' => 200,
                'price_type' => 'hourly',
                'service_area' => 'İzmir merkez ilçeler',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
