<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('series_id')->constrained('document_series'); // FK
            $table->unsignedBigInteger('number');
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->foreignId('customer_id')->constrained('contacts'); // FK
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();
            $table->decimal('exchange_rate', 15, 6)->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_total', 15, 2);
            $table->decimal('total', 15, 2);
            $table->string('status'); // draft, issued, etc
            $table->string('payment_status'); // unpaid, paid
            $table->foreignId('related_document_id')->nullable()->constrained('sales_documents')->nullOnDelete();
            $table->text('observations')->nullable();
            $table->string('electronic_uuid')->nullable(); // CUFE/Unique ID
            $table->string('hash')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_documents');
    }
};
