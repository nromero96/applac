<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriorityNotesToFeaturedQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('featured_quotations', function (Blueprint $table) {
            $table->string('priority')->after('quotation_id')->nullable();
            $table->text('notes')->after('quotation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('featured_quotations', function (Blueprint $table) {
            $table->dropColumn('priority');
            $table->dropColumn('notes');
        });
    }
}
