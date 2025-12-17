<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para Guías de Remisión Electrónicas
 */
return new class extends Migration
{
    public function up(): void
    {
        // Catálogo 20: Motivos de traslado
        Schema::create('despatch_transfer_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Catálogo 18: Modalidad de transporte
        Schema::create('transport_modalities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Transportistas
        Schema::create('transporters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('document_type', 10); // RUC, DNI
            $table->string('document_number', 20);
            $table->string('business_name');
            $table->string('registration_number')->nullable(); // Registro MTC
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['company_id', 'active']);
        });

        // Vehículos
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transporter_id')->nullable()->constrained()->nullOnDelete();
            $table->string('plate_number', 10);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('authorization_code')->nullable(); // Código autorización MTC
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['company_id', 'active']);
        });

        // Guías de Remisión
        Schema::create('despatch_advices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('series_id')->constrained('document_series')->cascadeOnDelete();
            $table->integer('number');
            $table->date('issue_date');
            $table->date('transfer_date');
            
            // Motivo y modalidad
            $table->foreignId('transfer_reason_id')->constrained('despatch_transfer_reasons');
            $table->foreignId('transport_modality_id')->constrained('transport_modalities');
            
            // Peso y bultos
            $table->decimal('gross_weight', 10, 2); // kg
            $table->integer('package_count');
            
            // Documento relacionado (opcional)
            $table->foreignId('sales_document_id')->nullable()->constrained()->nullOnDelete();
            
            // Origen
            $table->text('origin_address');
            $table->foreignId('origin_ubigeo_id')->constrained('ubigeos');
            
            // Destino
            $table->text('destination_address');
            $table->foreignId('destination_ubigeo_id')->constrained('ubigeos');
            
            // Destinatario
            $table->string('recipient_document_type', 10)->nullable();
            $table->string('recipient_document_number', 20)->nullable();
            $table->string('recipient_name')->nullable();
            
            // Transportista (para transporte público)
            $table->foreignId('transporter_id')->nullable()->constrained()->nullOnDelete();
            
            // Conductor
            $table->string('driver_document_type', 10)->nullable();
            $table->string('driver_document_number', 20)->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_license')->nullable();
            
            // Vehículo
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            
            // SUNAT
            $table->string('sunat_status')->default('pending'); // pending, accepted, rejected
            $table->text('observation')->nullable();
            
            // Estado
            $table->string('status')->default('draft'); // draft, issued, cancelled
            
            $table->timestamps();
            
            $table->unique(['series_id', 'number']);
            $table->index(['company_id', 'issue_date']);
            $table->index('sunat_status');
        });

        // Items de la guía
        Schema::create('despatch_advice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('despatch_advice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 10, 2);
            $table->string('unit_code', 10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('despatch_advice_items');
        Schema::dropIfExists('despatch_advices');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('transporters');
        Schema::dropIfExists('transport_modalities');
        Schema::dropIfExists('despatch_transfer_reasons');
    }
};
