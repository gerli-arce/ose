<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->index(); // SKU
            $table->string('barcode')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('unit_id')->constrained('unit_of_measures'); // FK Table name
            $table->boolean('is_service')->default(false);
            $table->decimal('cost_price', 15, 4)->nullable();
            $table->decimal('sale_price', 15, 4)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
