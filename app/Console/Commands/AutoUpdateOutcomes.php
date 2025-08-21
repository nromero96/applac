<?php

namespace App\Console\Commands;

use App\Services\AutoUpdateOutcomes as ServicesAutoUpdateOutcomes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoUpdateOutcomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotes:update-outcomes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar el result de los inquiries inactivos a Lost cada 24 horas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ServicesAutoUpdateOutcomes $service) {
        $res = $service->update_outcomes();
        $this->info($res);
        // $this->info("Update succesful.");
    }
}
