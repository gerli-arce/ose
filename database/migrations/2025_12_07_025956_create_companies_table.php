<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('trade_name')->nullable();
            $table->string('tax_id')->index();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('active')->default(true);
            $table->json('config_json')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
