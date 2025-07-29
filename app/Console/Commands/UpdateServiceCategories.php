<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Schema;

class UpdateServiceCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update service categories with missing fields';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Önce eksik kolonları ekleyelim
        if (!Schema::hasColumn('service_categories', 'color')) {
            Schema::table('service_categories', function ($table) {
                $table->string('color', 7)->default('#667eea')->after('icon');
            });
            $this->info('Color column added.');
        }

        if (!Schema::hasColumn('service_categories', 'is_featured')) {
            Schema::table('service_categories', function ($table) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            });
            $this->info('is_featured column added.');
        }

        if (!Schema::hasColumn('service_categories', 'order_index')) {
            Schema::table('service_categories', function ($table) {
                $table->integer('order_index')->default(0)->after('is_featured');
            });
            $this->info('order_index column added.');
        }

        // Kategorileri güncelleyelim
        $categories = [
            'Kamera Montaj' => ['color' => '#e74c3c', 'order_index' => 1],
            'Elektrik Tesisatı' => ['color' => '#f39c12', 'order_index' => 2],
            'Su Tesisatı' => ['color' => '#3498db', 'order_index' => 3],
            'Klima Montaj' => ['color' => '#2ecc71', 'order_index' => 4],
            'Beyaz Eşya Tamiri' => ['color' => '#9b59b6', 'order_index' => 5],
            'Bilgisayar Tamiri' => ['color' => '#34495e', 'order_index' => 6],
            'Televizyon Tamiri' => ['color' => '#e67e22', 'order_index' => 7],
            'Boyacı' => ['color' => '#1abc9c', 'order_index' => 8],
        ];

        foreach ($categories as $name => $data) {
            $category = ServiceCategory::where('name', $name)->first();
            if ($category) {
                $category->update([
                    'color' => $data['color'],
                    'order_index' => $data['order_index'],
                    'is_featured' => false
                ]);
                $this->info("Updated: {$name}");
            }
        }

        $this->info('All service categories updated successfully!');
        return 0;
    }
}
