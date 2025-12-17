<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo SUNAT Nº 07: Códigos de tipo de afectación del IGV
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_affectation_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();  // Código SUNAT (10, 11, 12, 13, 20, 21, 30, 31, 32, 33, 34, 35, 36, 40)
            $table->string('name');               // Nombre descriptivo
            $table->string('letter', 1);          // Letra para factura (S = Gravado, E = Exonerado, O = Inafecto, Z = Exportación)
            $table->string('tribute_code', 4);    // Código de tributo relacionado (1000, 9997, 9998)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_affectation_types');
    }
};
