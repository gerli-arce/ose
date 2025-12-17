<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla para registrar histórico de intentos de envío a SUNAT
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sunat_send_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_document_id')->constrained()->cascadeOnDelete();
            $table->string('attempt_type')->default('send'); // send, check_status, resend
            $table->string('status'); // success, error, pending
            $table->string('response_code')->nullable();
            $table->text('response_message')->nullable();
            $table->string('ticket')->nullable();
            $table->string('xml_path')->nullable();
            $table->string('cdr_path')->nullable();
            $table->text('error_details')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('attempted_at');
            $table->timestamps();
            
            $table->index(['sales_document_id', 'attempted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sunat_send_attempts');
    }
};
