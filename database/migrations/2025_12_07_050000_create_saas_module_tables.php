<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop if exists to fix conflict with lost migrations
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('saas_invoices');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        Schema::enableForeignKeyConstraints();

        // Plans (e.g., Basic, Pro)
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('duration_days')->default(30);
            // Limits (-1 for unlimited)
            $table->integer('limit_users')->default(1);
            $table->integer('limit_documents')->default(100);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Subscriptions
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained();
            $table->date('starts_at');
            $table->date('ends_at');
            $table->string('status'); // trial, active, expired, cancelled
            // Snapshot of limits at time of subscription (optional, but good for locks)
            $table->integer('limit_users');
            $table->integer('limit_documents');
            $table->timestamps();
        });

        // SaaS Billing (Invoices FROM Admin TO Tenant)
        Schema::create('saas_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained(); // The tenant being billed
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->string('status'); // paid, unpaid, overdue
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_invoices');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
    }
};
