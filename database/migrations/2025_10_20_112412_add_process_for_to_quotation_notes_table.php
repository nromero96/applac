<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessForToQuotationNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotation_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('processed_by_user_id')->after('contacted_via')->nullable();
            $table->string('processed_by_type')->after('contacted_via')->nullable(); // auto - manual
            $table->string('process_for')->after('contacted_via')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotation_notes', function (Blueprint $table) {
            $table->dropColumn('processed_by_type');
            $table->dropColumn('processed_by_user_id');
            $table->dropColumn('process_for');
        });
    }
}
