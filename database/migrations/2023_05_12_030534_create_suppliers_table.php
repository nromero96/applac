<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string("company_logo")->default("icon-company-default.png");
            $table->string("company_name");
            $table->string("company_address");
            $table->integer("country_id");
            $table->integer("state_id");
            $table->string("city");
            $table->string("company_website")->nullable();
            $table->integer("company_rating")->default(0);
            $table->string("document_one")->nullable();
            $table->string("document_two")->nullable();
            $table->string("document_three")->nullable();
            $table->text("company_note")->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
