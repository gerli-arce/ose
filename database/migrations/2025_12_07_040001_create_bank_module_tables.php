<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_type')->nullable(); // Checking, Savings
            $table->foreignId('currency_id')->constrained();
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->string('holder_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // deposit, withdrawal, transfer_in, transfer_out
            $table->decimal('amount', 15, 2);
            $table->string('reference')->nullable(); // Operation number
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->boolean('is_reconciled')->default(false);
            $table->timestamp('reconciled_at')->nullable();
            // Morphable relation
            $table->nullableMorphs('related');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
        Schema::dropIfExists('bank_accounts');
    }
};
