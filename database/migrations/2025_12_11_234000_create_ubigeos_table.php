<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla de ubigeos de Perú (INEI)
 * Estructura jerárquica: Departamento > Provincia > Distrito
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubigeos', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6)->unique(); // Código INEI de 6 dígitos
            $table->string('department_code', 2); // 2 primeros dígitos
            $table->string('province_code', 4)->nullable(); // 4 primeros dígitos
            $table->string('district_code', 6)->nullable(); // 6 dígitos completos
            $table->string('name'); // Nombre del departamento/provincia/distrito
            $table->enum('level', ['department', 'province', 'district']); // Nivel jerárquico
            $table->foreignId('parent_id')->nullable()->constrained('ubigeos')->nullOnDelete();
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['department_code', 'level']);
            $table->index(['province_code', 'level']);
            $table->index('parent_id');
        });

        // Actualizar tabla companies para usar ubigeo_id
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('ubigeo_id')->nullable()->after('id')->constrained('ubigeos')->nullOnDelete();
        });

        // Actualizar tabla contacts para usar ubigeo_id
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('ubigeo_id')->nullable()->after('id')->constrained('ubigeos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['ubigeo_id']);
            $table->dropColumn('ubigeo_id');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['ubigeo_id']);
            $table->dropColumn('ubigeo_id');
        });

        Schema::dropIfExists('ubigeos');
    }
};
