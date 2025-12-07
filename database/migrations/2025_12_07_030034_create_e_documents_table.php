<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('e_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_document_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // sunat, pse
            $table->string('xml_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->dateTime('signed_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->string('response_status')->nullable(); // accepted, rejected, pending
            $table->string('response_code')->nullable();
            $table->text('response_message')->nullable();
            $table->string('cdr_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_documents');
    }
};
