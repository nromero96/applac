<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAutoToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->boolean('auto_unqualified')->default(false)->after('prev_dept');
            $table->string('url_pdf_sent')->nullable()->after('prev_dept');
            $table->boolean('auto_quoted')->default(false)->after('prev_dept');
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
            $table->dropColumn('auto_unqualified');
            $table->dropColumn('url_pdf_sent');
            $table->dropColumn('auto_quoted');
        });
    }
}
