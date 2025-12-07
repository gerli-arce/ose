<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('billing_period'); // monthly, yearly
            $table->boolean('auto_renew')->default(true);
            $table->string('status'); // active, trial, etc
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_subscriptions');
    }
};
