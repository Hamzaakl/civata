<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Kamera Montaj',
                'slug' => 'kamera-montaj',
                'description' => 'Güvenlik kamerası kurulum ve montaj hizmetleri',
                'icon' => 'fa-video',
                'sort_order' => 1,
            ],
            [
                'name' => 'Elektrik Tesisatı',
                'slug' => 'elektrik-tesisati',
                'description' => 'Elektrik arıza, priz montaj ve elektrik tesisatı işleri',
                'icon' => 'fa-bolt',
                'sort_order' => 2,
            ],
            [
                'name' => 'Su Tesisatı',
                'slug' => 'su-tesisati',
                'description' => 'Su kaçağı, tesisat arıza ve su tesisatı işleri',
                'icon' => 'fa-tint',
                'sort_order' => 3,
            ],
            [
                'name' => 'Klima Montaj',
                'slug' => 'klima-montaj',
                'description' => 'Klima kurulum, bakım ve tamir hizmetleri',
                'icon' => 'fa-wind',
                'sort_order' => 4,
            ],
            [
                'name' => 'Beyaz Eşya Tamiri',
                'slug' => 'beyaz-esya-tamiri',
                'description' => 'Çamaşır makinesi, bulaşık makinesi, buzdolabı tamiri',
                'icon' => 'fa-home',
                'sort_order' => 5,
            ],
            [
                'name' => 'Bilgisayar Tamiri',
                'slug' => 'bilgisayar-tamiri',
                'description' => 'Bilgisayar, laptop tamir ve teknik destek',
                'icon' => 'fa-laptop',
                'sort_order' => 6,
            ],
            [
                'name' => 'Televizyon Tamiri',
                'slug' => 'televizyon-tamiri',
                'description' => 'LED, LCD, OLED televizyon tamir hizmetleri',
                'icon' => 'fa-tv',
                'sort_order' => 7,
            ],
            [
                'name' => 'Boyacı',
                'slug' => 'boyaci',
                'description' => 'İç ve dış cephe boyama işleri',
                'icon' => 'fa-paint-brush',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }
    }
}
