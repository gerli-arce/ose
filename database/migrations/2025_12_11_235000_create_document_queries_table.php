<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla para cachear consultas de RUC/DNI
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_queries', function (Blueprint $table) {
            $table->id();
            $table->string('document_type', 10); // RUC, DNI
            $table->string('document_number', 20)->unique();
            $table->string('name')->nullable();
            $table->string('business_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('address')->nullable();
            $table->string('ubigeo', 6)->nullable();
            $table->string('department')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('condition')->nullable(); // ACTIVO, BAJA, etc.
            $table->string('state')->nullable(); // HABIDO, NO HABIDO
            $table->json('raw_data')->nullable(); // Respuesta completa de la API
            $table->string('source')->nullable(); // apis.net.pe, apiperu.dev, etc.
            $table->timestamp('queried_at');
            $table->timestamps();
            
            $table->index(['document_type', 'document_number']);
            $table->index('queried_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_queries');
    }
};
