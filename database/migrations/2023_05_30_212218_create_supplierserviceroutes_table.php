<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierserviceroutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplierserviceroutes', function (Blueprint $table) {
            $table->id();
            $table->string('supplierservice_id');
            $table->string('origin_country')->nullable();
            $table->string('origin_state')->nullable();
            $table->string('origin_city')->nullable();
            $table->string('destination_country')->nullable();
            $table->string('destination_state')->nullable();
            $table->string('destination_city')->nullable();
            $table->string('crossing')->nullable();
            $table->string('return_route')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplierserviceroutes');
    }
}
