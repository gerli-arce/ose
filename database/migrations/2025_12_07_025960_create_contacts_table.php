<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // customer, supplier, both
            $table->string('name');
            $table->string('business_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('tax_id')->index();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->integer('payment_terms')->nullable(); // days
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
