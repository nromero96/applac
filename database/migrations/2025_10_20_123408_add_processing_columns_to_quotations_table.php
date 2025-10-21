<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessingColumnsToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->unsignedBigInteger('processed_by_user_id')->after('points')->nullable();
            $table->string('processed_by_type')->after('points')->nullable(); // auto - manual
            $table->string('process_for')->after('points')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('processed_by_user_id');
            $table->dropColumn('process_for');
            $table->dropColumn('processed_by_type');
        });
    }
}
