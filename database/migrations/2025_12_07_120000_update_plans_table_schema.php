<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('plans', 'slug')) $table->dropColumn('slug');
            if (Schema::hasColumn('plans', 'duration_days')) $table->dropColumn('duration_days');
            if (Schema::hasColumn('plans', 'limit_users')) $table->dropColumn('limit_users');
            if (Schema::hasColumn('plans', 'limit_documents')) $table->dropColumn('limit_documents');
            if (Schema::hasColumn('plans', 'active')) $table->dropColumn('active');
            
            // Rename price to price_monthly if exists, else add it
            if (Schema::hasColumn('plans', 'price')) {
                $table->renameColumn('price', 'price_monthly');
            } else {
                $table->decimal('price_monthly', 15, 2)->nullable()->after('name');
            }
            
            // Add price_yearly
            if (!Schema::hasColumn('plans', 'price_yearly')) {
                $table->decimal('price_yearly', 15, 2)->nullable()->after('price_monthly');
            }
        });
        
        // Ensure price_monthly is decimal(15,2)
        Schema::table('plans', function (Blueprint $table) {
             $table->decimal('price_monthly', 15, 2)->change();
        });
    }

    public function down(): void
    {
        // simplistic rollback
    }
};
