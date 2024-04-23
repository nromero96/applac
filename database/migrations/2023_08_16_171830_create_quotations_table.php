<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_user_id')->nullable();
            $table->unsignedBigInteger('guest_user_id')->nullable();
            $table->string('mode_of_transport', 30);
            $table->string('cargo_type', 50)->nullable();
            $table->string('service_type', 50);
            $table->integer('origin_country_id');
            $table->string('origin_address')->nullable();
            $table->string('origin_city')->nullable();
            $table->integer('origin_state_id')->nullable();
            $table->string('origin_zip_code')->nullable();
            $table->string('origin_airportorport')->nullable();
            $table->integer('destination_country_id');
            $table->string('destination_address')->nullable();
            $table->string('destination_city')->nullable();
            $table->integer('destination_state_id')->nullable();
            $table->string('destination_zip_code')->nullable();
            $table->string('destination_airportorport')->nullable();
            $table->integer('total_qty')->nullable();
            $table->string('total_actualweight')->nullable();
            $table->string('total_volum_weight')->nullable();
            $table->string('tota_chargeable_weight')->nullable();
            $table->string('shipping_date')->nullable();
            $table->string('no_shipping_date',5);
            $table->decimal('declared_value', 8, 2);
            $table->string('insurance_required',5);
            $table->string('currency',30);
            $table->string('rating',30)->nullable();
            $table->string('status',30)->default('Processing');
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
