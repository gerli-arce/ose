<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo SUNAT Nº 06: Códigos de tipos de documentos de identidad
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('identity_document_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique(); // Código SUNAT (0, 1, 4, 6, 7, A, B, C, D, E)
            $table->string('name');              // Nombre descriptivo
            $table->string('abbreviation', 10)->nullable(); // DNI, RUC, CE, etc.
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Agregar columna a contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('identity_document_type_id')
                  ->nullable()
                  ->after('company_id')
                  ->constrained('identity_document_types')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['identity_document_type_id']);
            $table->dropColumn('identity_document_type_id');
        });
        
        Schema::dropIfExists('identity_document_types');
    }
};
