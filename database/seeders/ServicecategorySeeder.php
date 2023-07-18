<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicecategory;

class ServicecategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                Servicecategory::create([
                    'servicecategory_name' => 'Ground',
                    'servicecategory_color' => '#ffc107',
                ]);
                Servicecategory::create([
                    'servicecategory_name' => 'Ocean',
                    'servicecategory_color' => '#29b6f6',
                ]);
                Servicecategory::create([
                    'servicecategory_name' => 'Air',
                    'servicecategory_color' => '#26a69a',
                ]);
                Servicecategory::create([
                    'servicecategory_name' => 'Other',
                    'servicecategory_color' => '#5c6bc0',
                ]);

    }
}
