<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemperatureTypeColumnToCargoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cargo_details', function (Blueprint $table) {
            $table->string('temperature_type')->nullable()->after('temperature');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cargo_details', function (Blueprint $table) {
            $table->dropColumn('temperature_type');
        });
    }
}
