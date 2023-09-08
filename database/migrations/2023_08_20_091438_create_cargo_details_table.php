<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->string('package_type');
            $table->integer('qty');
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('dimensions_unit')->nullable();
            $table->string('per_piece')->nullable();
            $table->string('item_total_weight')->nullable();
            $table->string('weight_unit')->nullable();
            $table->string('item_total_volume_weight_cubic_meter')->nullable();
            $table->string('cargo_description')->nullable();
            $table->string('electric_vehicle',5)->nullable();
            $table->string('dangerous_cargo',5)->nullable();
            $table->text('dc_imoclassification_1')->nullable();
            $table->string('dc_unnumber_1')->nullable();
            $table->text('dc_imoclassification_2')->nullable();
            $table->string('dc_unnumber_2')->nullable();
            $table->text('dc_imoclassification_3')->nullable();
            $table->string('dc_unnumber_3')->nullable();
            $table->text('dc_imoclassification_4')->nullable();
            $table->string('dc_unnumber_4')->nullable();
            $table->text('dc_imoclassification_5')->nullable();
            $table->string('dc_unnumber_5')->nullable();
            $table->timestamps();

            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cargo_details');
    }
}
