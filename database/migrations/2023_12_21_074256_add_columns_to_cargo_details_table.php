<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCargoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cargo_details', function (Blueprint $table) {
            // Agregar columna 'temperature' después de 'package_type'
            $table->string('temperature')->nullable()->after('package_type');

            // Agregar columna 'details_shipment' después de 'qty'
            $table->text('details_shipment')->nullable()->after('qty');
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
            // Eliminar columna 'temperature'
            $table->dropColumn('temperature');

            // Eliminar columna 'details_shipment'
            $table->dropColumn('details_shipment');
        });
    }
}
