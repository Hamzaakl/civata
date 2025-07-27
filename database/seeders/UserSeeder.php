<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin kullanıcı
        User::create([
            'name' => 'Admin',
            'email' => 'admin@civata.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'phone' => '0555 123 4567',
            'city' => 'İstanbul',
            'is_verified' => true,
        ]);

        // Hizmet sağlayıcılar
        $providers = [
            [
                'name' => 'Ahmet Kaya - Elektrikçi',
                'email' => 'ahmet@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'service_provider',
                'phone' => '0532 111 2233',
                'city' => 'İstanbul',
                'address' => 'Kadıköy, İstanbul',
                'bio' => '15 yıllık tecrübeli elektrikçi. Tüm elektrik işleriniz için hizmetinizdeyim.',
                'is_verified' => true,
                'rating' => 4.8,
                'total_reviews' => 45,
            ],
            [
                'name' => 'Mehmet Öz - Tesisatçı',
                'email' => 'mehmet@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'service_provider',
                'phone' => '0533 444 5566',
                'city' => 'İstanbul',
                'address' => 'Beşiktaş, İstanbul',
                'bio' => 'Su tesisatı uzmanı. 7/24 acil servis hizmeti veriyorum.',
                'is_verified' => true,
                'rating' => 4.5,
                'total_reviews' => 32,
            ],
            [
                'name' => 'Ali Yılmaz - Kameraman',
                'email' => 'ali@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'service_provider',
                'phone' => '0534 777 8899',
                'city' => 'Ankara',
                'address' => 'Çankaya, Ankara',
                'bio' => 'Güvenlik kamerası kurulum uzmanı. Profesyonel çözümler sunuyorum.',
                'is_verified' => true,
                'rating' => 4.9,
                'total_reviews' => 67,
            ],
            [
                'name' => 'Fatma Demir - Temizlik',
                'email' => 'fatma@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'service_provider',
                'phone' => '0535 222 3344',
                'city' => 'İzmir',
                'address' => 'Konak, İzmir',
                'bio' => 'Ev temizliği konusunda uzmanım. Güvenilir ve titiz çalışırım.',
                'is_verified' => true,
                'rating' => 4.7,
                'total_reviews' => 28,
            ],
        ];

        foreach ($providers as $provider) {
            User::create($provider);
        }

        // Müşteri kullanıcılar
        $customers = [
            [
                'name' => 'Zeynep Özkan',
                'email' => 'zeynep@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'customer',
                'phone' => '0536 123 4567',
                'city' => 'İstanbul',
                'address' => 'Şişli, İstanbul',
            ],
            [
                'name' => 'Can Demir',
                'email' => 'can@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'customer',
                'phone' => '0537 987 6543',
                'city' => 'Ankara',
                'address' => 'Kızılay, Ankara',
            ],
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }
    }
}
