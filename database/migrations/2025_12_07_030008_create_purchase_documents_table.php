<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('contacts');
            $table->foreignId('document_type_id')->constrained();
            $table->string('series');
            $table->string('number');
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->foreignId('currency_id')->constrained();
            $table->decimal('exchange_rate', 15, 6)->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_total', 15, 2);
            $table->decimal('total', 15, 2);
            $table->string('status'); // registered, paid, canceled
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_documents');
    }
};
