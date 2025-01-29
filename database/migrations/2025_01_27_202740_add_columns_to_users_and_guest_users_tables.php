<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersAndGuestUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('job_title')->after('phone')->nullable();
            $table->string('business_role',150)->after('job_title')->nullable();
            $table->string('ea_shipments',100)->after('business_role')->nullable();
        });

        Schema::table('guest_users', function (Blueprint $table) {
            $table->string('job_title')->after('phone')->nullable();
            $table->string('business_role',150)->after('job_title')->nullable();
            $table->string('ea_shipments',100)->after('business_role')->nullable();
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
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['job_title', 'business_role', 'ea_shipments']);
            });
    
            Schema::table('guest_users', function (Blueprint $table) {
                $table->dropColumn(['job_title', 'business_role', 'ea_shipments']);
            });
        });
    }
}
