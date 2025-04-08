<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotePendingEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_pending_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade'); // Relación segura
            $table->string('type'); // Ej: unqualified, reminder, etc.
            $table->string('customer_name');
            $table->string('email');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable(); // text para errores largos
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quote_pending_emails');
    }
}
