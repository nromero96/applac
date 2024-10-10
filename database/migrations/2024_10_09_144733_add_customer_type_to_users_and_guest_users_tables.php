<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerTypeToUsersAndGuestUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar la columna 'customer_type' a la tabla 'users'
            Schema::table('users', function (Blueprint $table) {
                $table->string('customer_type',70)->after('id')->nullable(); // Puedes cambiar nullable si no es necesario
            });

            // Agregar la columna 'customer_type' a la tabla 'guest_users'
            Schema::table('guest_users', function (Blueprint $table) {
                $table->string('customer_type',70)->after('id')->nullable(); // Puedes cambiar nullable si no es necesario
            });
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
            // Eliminar la columna 'customer_type' de la tabla 'users'
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('customer_type');
            });

            // Eliminar la columna 'customer_type' de la tabla 'guest_users'
            Schema::table('guest_users', function (Blueprint $table) {
                $table->dropColumn('customer_type');
            });
        });
    }
}
