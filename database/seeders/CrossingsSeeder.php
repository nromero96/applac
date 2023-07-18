<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Crossing;

class CrossingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //142 = Mexico
        //create
        Crossing::create([
            'crossing_name' => 'Laredo / Nuevo Laredo',
            'crossing_country' => '142',
        ]);
        Crossing::create([
            'crossing_name' => 'El Paso / Ciudad Juarez',
            'crossing_country' => '142',
        ]);
        Crossing::create([
            'crossing_name' => 'Calexico / Mexicali',
            'crossing_country' => '142',
        ]);
        Crossing::create([
            'crossing_name' => 'Brownsville / Matamoros',
            'crossing_country' => '142',
        ]);
        Crossing::create([
            'crossing_name' => 'Mc Allen / Reynosa',
            'crossing_country' => '142',
        ]);
        Crossing::create([
            'crossing_name' => 'Nogales / Nogales',
            'crossing_country' => '142',
        ]);
        Crossing::create([
            'crossing_name' => 'San Diego / Tijuana',
            'crossing_country' => '142',
        ]);
        

    }
}
