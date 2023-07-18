<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliercontactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliercontacts', function (Blueprint $table) {
            $table->id();
            $table->integer("supplier_id");
            $table->string("contact_name");
            $table->string("contact_email");
            $table->string("contact_position")->nullable();
            $table->string("contact_main")->nullable();
            $table->string("contact_typeone")->nullable();
            $table->string("contact_typeone_value")->nullable();
            $table->string("contact_typetwo")->nullable();
            $table->string("contact_typetwo_value")->nullable();
            $table->string("contact_typethree")->nullable();
            $table->string("contact_typethree_value")->nullable();
            $table->string("contact_typefour")->nullable();
            $table->string("contact_typefour_value")->nullable();
            $table->string("contact_typefive")->nullable();
            $table->string("contact_typefive_value")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliercontacts');
    }
}
