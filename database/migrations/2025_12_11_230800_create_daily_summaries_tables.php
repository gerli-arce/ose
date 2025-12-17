<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla para registrar Resúmenes Diarios de Boletas enviados a SUNAT
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('identifier'); // RC-YYYYMMDD-##### 
            $table->date('summary_date'); // Fecha del resumen (generación)
            $table->date('reference_date'); // Fecha de los documentos
            $table->string('ticket')->nullable(); // Ticket de SUNAT
            $table->string('status')->default('pending'); // pending, sent, accepted, rejected
            $table->string('response_code')->nullable();
            $table->text('response_message')->nullable();
            $table->string('xml_path')->nullable();
            $table->string('cdr_path')->nullable();
            $table->unsignedInteger('total_documents')->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('status_checked_at')->nullable();
            $table->timestamps();
            
            $table->unique(['company_id', 'identifier']);
            $table->index(['company_id', 'reference_date']);
        });

        // Items del resumen diario
        Schema::create('daily_summary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_summary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sales_document_id')->constrained()->cascadeOnDelete();
            $table->string('document_type_code'); // 03 para boleta, 07 para NC boleta
            $table->string('series');
            $table->unsignedBigInteger('start_number'); // Correlativo inicial
            $table->unsignedBigInteger('end_number'); // Correlativo final (mismo si es único)
            $table->string('status_code'); // 1=Agregar, 2=Modificar, 3=Anular
            $table->decimal('total_gravadas', 14, 2)->default(0);
            $table->decimal('total_exoneradas', 14, 2)->default(0);
            $table->decimal('total_inafectas', 14, 2)->default(0);
            $table->decimal('total_exportacion', 14, 2)->default(0);
            $table->decimal('total_gratuitas', 14, 2)->default(0);
            $table->decimal('total_igv', 14, 2)->default(0);
            $table->decimal('total_isc', 14, 2)->default(0);
            $table->decimal('total_otros', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->timestamps();
        });

        // Agregar referencia al resumen en sales_documents
        Schema::table('sales_documents', function (Blueprint $table) {
            $table->foreignId('daily_summary_id')
                  ->nullable()
                  ->after('voided_at')
                  ->constrained('daily_summaries')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sales_documents', function (Blueprint $table) {
            $table->dropForeign(['daily_summary_id']);
            $table->dropColumn('daily_summary_id');
        });
        
        Schema::dropIfExists('daily_summary_items');
        Schema::dropIfExists('daily_summaries');
    }
};
