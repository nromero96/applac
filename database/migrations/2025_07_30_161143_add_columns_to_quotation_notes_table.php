<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToQuotationNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotation_notes', function (Blueprint $table) {
            $table->string('followup_channel')->nullable()->after('note');
            $table->string('followup_feedback')->nullable()->after('note');
            $table->string('followup_comment')->nullable()->after('note');
            $table->string('lost_reason')->nullable()->after('note');
            $table->string('update_type')->nullable()->default('changed')->after('note'); // changed / unchanged
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
            $table->dropColumn('followup_channel');
            $table->dropColumn('followup_feedback');
            $table->dropColumn('followup_comment');
            $table->dropColumn('lost_reason');
            $table->dropColumn('update_type');
        });
    }
}
