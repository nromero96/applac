<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->text('cargo_description')->nullable()->after('assigned_user_id');
            $table->boolean('recovered_account')->default(false)->after('assigned_user_id');
            $table->boolean('is_internal_inquiry')->default(false)->after('assigned_user_id');
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
            $table->dropColumn('recovered_account');
            $table->dropColumn('is_internal_inquiry');
            $table->dropColumn('cargo_description');
        });
    }
}
