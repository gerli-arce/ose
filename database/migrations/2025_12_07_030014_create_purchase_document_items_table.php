<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_document_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 15, 4);
            $table->foreignId('unit_id')->constrained('unit_of_measures');
            $table->decimal('unit_cost', 15, 4);
            $table->decimal('line_subtotal', 15, 2);
            $table->decimal('line_tax_total', 15, 2);
            $table->decimal('line_total', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_document_items');
    }
};
