<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('referred_by')->after('source')->nullable()->default(false);
            $table->boolean('recovered_account')->after('source')->nullable()->default(false);
            $table->json('network')->after('source')->nullable();
            $table->decimal('score', 5, 2)->after('source')->nullable();
            $table->string('tier')->after('source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referred_by');
            $table->dropColumn('recovered_account');
            $table->dropColumn('network');
            $table->dropColumn('score');
            $table->dropColumn('tier');
        });
    }
}
