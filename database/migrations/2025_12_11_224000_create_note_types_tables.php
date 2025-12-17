<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo SUNAT Nº 09: Códigos de tipo de nota de crédito electrónica
 * Catálogo SUNAT Nº 10: Códigos de tipo de nota de débito electrónica
 */
return new class extends Migration
{
    public function up(): void
    {
        // Tabla para tipos de nota de crédito (Catálogo 09)
        Schema::create('credit_note_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->boolean('affects_stock')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Tabla para tipos de nota de débito (Catálogo 10)
        Schema::create('debit_note_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Agregar campos para NC/ND en sales_documents
        Schema::table('sales_documents', function (Blueprint $table) {
            // Tipo de nota (referencia a catálogo 09 o 10)
            $table->foreignId('credit_note_type_id')
                  ->nullable()
                  ->after('related_document_id')
                  ->constrained('credit_note_types')
                  ->nullOnDelete();
            
            $table->foreignId('debit_note_type_id')
                  ->nullable()
                  ->after('credit_note_type_id')
                  ->constrained('debit_note_types')
                  ->nullOnDelete();
            
            // Motivo/descripción del motivo
            $table->text('note_reason')->nullable()->after('debit_note_type_id');
            
            // Sunat status si no existe
            if (!Schema::hasColumn('sales_documents', 'sunat_status')) {
                $table->string('sunat_status')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales_documents', function (Blueprint $table) {
            $table->dropForeign(['credit_note_type_id']);
            $table->dropForeign(['debit_note_type_id']);
            $table->dropColumn(['credit_note_type_id', 'debit_note_type_id', 'note_reason']);
        });
        
        Schema::dropIfExists('debit_note_types');
        Schema::dropIfExists('credit_note_types');
    }
};
