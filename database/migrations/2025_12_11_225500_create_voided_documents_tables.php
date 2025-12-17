<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla para registrar Comunicaciones de Baja enviadas a SUNAT
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voided_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('identifier'); // RA-YYYYMMDD-##### 
            $table->date('voided_date'); // Fecha de la comunicación
            $table->date('reference_date'); // Fecha del documento afectado
            $table->string('ticket')->nullable(); // Ticket de SUNAT
            $table->string('status')->default('pending'); // pending, sent, accepted, rejected
            $table->string('response_code')->nullable();
            $table->text('response_message')->nullable();
            $table->string('cdr_path')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('status_checked_at')->nullable();
            $table->timestamps();
            
            $table->unique(['company_id', 'identifier']);
        });

        // Items de la comunicación de baja
        Schema::create('voided_document_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voided_document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sales_document_id')->constrained()->cascadeOnDelete();
            $table->string('document_type_code'); // 01, 03, 07, 08
            $table->string('series'); // F001, B001
            $table->unsignedBigInteger('number'); // Correlativo
            $table->text('reason'); // Motivo de la baja
            $table->timestamps();
        });

        // Agregar campo de anulación a sales_documents
        Schema::table('sales_documents', function (Blueprint $table) {
            $table->foreignId('voided_document_id')
                  ->nullable()
                  ->after('debit_note_type_id')
                  ->constrained('voided_documents')
                  ->nullOnDelete();
            $table->timestamp('voided_at')->nullable()->after('voided_document_id');
        });
    }

    public function down(): void
    {
        Schema::table('sales_documents', function (Blueprint $table) {
            $table->dropForeign(['voided_document_id']);
            $table->dropColumn(['voided_document_id', 'voided_at']);
        });
        
        Schema::dropIfExists('voided_document_items');
        Schema::dropIfExists('voided_documents');
    }
};
