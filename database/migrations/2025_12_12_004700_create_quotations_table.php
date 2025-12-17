<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla de cotizaciones
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained('contacts');
            $table->foreignId('currency_id')->constrained();
            
            // Numeración
            $table->string('series', 10)->default('COT');
            $table->unsignedInteger('number');
            
            // Fechas
            $table->date('issue_date');
            $table->date('expiry_date');
            
            // Vendedor
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Totales
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_total', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('exchange_rate', 15, 6)->default(1);
            
            // Estado: draft, sent, accepted, rejected, expired, invoiced
            $table->string('status', 20)->default('draft');
            
            // Referencia a factura si fue convertida
            $table->foreignId('sales_document_id')->nullable()->constrained()->nullOnDelete();
            
            // Términos y condiciones
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            
            // Validez en días
            $table->unsignedInteger('validity_days')->default(15);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índice para numeración única por empresa
            $table->unique(['company_id', 'series', 'number']);
        });

        // Tabla de items de cotización
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            
            // Descripción (puede ser diferente al producto)
            $table->text('description');
            $table->string('unit_code', 10)->default('NIU');
            
            // Cantidades y precios
            $table->decimal('quantity', 15, 4);
            $table->decimal('unit_price', 15, 4);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            
            // Orden de visualización
            $table->unsignedInteger('sort_order')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
