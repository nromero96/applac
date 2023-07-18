<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //reference the servicecategory_id from the servicecategory table
        Service::create([
            'service_name' => 'LTL - Van',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'FTL - Van',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'Flatbed',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'RGN',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'Conestoga',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'Double Drop / Lowboy',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'Step Deck',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => '3PL Truck Broker',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'Reefer',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'Hazmat',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'Drayage',
            'servicecategory_id' => '1',
        ]);
        Service::create([
            'service_name' => 'Intermodal',
            'servicecategory_id' => '1',
        ]);

        //reference the servicecategory_id from the servicecategory table
        Service::create([
            'service_name' => 'Reefer',
            'servicecategory_id' => '2',
        ]);
        Service::create([
            'service_name' => 'Hazmat',
            'servicecategory_id' => '2',
        ]);

        Service::create([
            'service_name' => 'Ocean Charter',
            'servicecategory_id' => '2',
        ]);
        Service::create([
            'service_name' => 'FCL',
            'servicecategory_id' => '2',
        ]);
        Service::create([
            'service_name' => 'LCL',
            'servicecategory_id' => '2',
        ]);
        Service::create([
            'service_name' => 'RORO',
            'servicecategory_id' => '2',
        ]);
        
        //reference the servicecategory_id from the servicecategory table
        Service::create([
            'service_name' => 'Air Charter',
            'servicecategory_id' => '3',
        ]);
        Service::create([
            'service_name' => 'Reefer',
            'servicecategory_id' => '3',
        ]);
        Service::create([
            'service_name' => 'Hazmat',
            'servicecategory_id' => '3',
        ]);

        //reference the servicecategory_id from the servicecategory table
        Service::create([
            'service_name' => 'Agent / Freight Forwarder',
            'servicecategory_id' => '4',
        ]);
        Service::create([
            'service_name' => 'Customs Broker (General)',
            'servicecategory_id' => '4',
        ]);
        Service::create([
            'service_name' => 'Mexican Customs Broker',
            'servicecategory_id' => '4',
        ]);
        Service::create([
            'service_name' => 'Stuffing Terminals / CFS',
            'servicecategory_id' => '4',
        ]);
        Service::create([
            'service_name' => 'Riggers',
            'servicecategory_id' => '4',
        ]);

    }
}
