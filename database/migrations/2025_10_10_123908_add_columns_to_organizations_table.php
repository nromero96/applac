<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('referred_by')->after('addresses')->nullable()->default(false);
            $table->boolean('recovered_account')->after('addresses')->nullable()->default(false);
            $table->json('network')->after('addresses')->nullable();
            $table->foreignId('country_id')->after(('addresses'))->nullable()->constrained()->nullOnDelete();
            $table->decimal('score', 5, 2)->after('addresses')->nullable();
            $table->string('tier')->after('addresses')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('referred_by');
            $table->dropColumn('recovered_account');
            $table->dropColumn('network');
            $table->dropConstrainedForeignId('country_id');
            $table->dropColumn('score');
            $table->dropColumn('tier');
        });
    }
}
