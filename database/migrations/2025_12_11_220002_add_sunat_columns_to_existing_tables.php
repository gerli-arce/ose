<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega columnas adicionales para compatibilidad SUNAT
 */
return new class extends Migration
{
    public function up(): void
    {
        // Agregar campos SUNAT a document_types
        Schema::table('document_types', function (Blueprint $table) {
            $table->string('sunat_code', 2)->nullable()->after('code'); // Código oficial SUNAT
            $table->string('category')->nullable()->after('name');       // sales, purchase, note, guide
            $table->boolean('requires_customer_ruc')->default(false)->after('affects_stock');
            $table->boolean('is_electronic')->default(true)->after('requires_customer_ruc');
        });

        // Agregar campos SUNAT a unit_of_measures
        Schema::table('unit_of_measures', function (Blueprint $table) {
            $table->string('sunat_code', 5)->nullable()->after('code'); // Código SUNAT puede variar
            $table->string('symbol', 10)->nullable()->after('name');
            $table->boolean('active')->default(true)->after('symbol');
        });
    }

    public function down(): void
    {
        Schema::table('document_types', function (Blueprint $table) {
            $table->dropColumn(['sunat_code', 'category', 'requires_customer_ruc', 'is_electronic']);
        });

        Schema::table('unit_of_measures', function (Blueprint $table) {
            $table->dropColumn(['sunat_code', 'symbol', 'active']);
        });
    }
};
