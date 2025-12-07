<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('sunat_sol_user')->nullable()->after('active');
            $table->string('sunat_sol_password')->nullable()->after('sunat_sol_user');
            $table->string('sunat_cert_path')->nullable()->after('sunat_sol_password');
            $table->string('sunat_cert_password')->nullable()->after('sunat_cert_path');
            $table->string('sunat_env')->default('beta')->after('sunat_cert_password');
            $table->string('logo_path')->nullable()->after('sunat_env');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'sunat_sol_user',
                'sunat_sol_password',
                'sunat_cert_path',
                'sunat_cert_password',
                'sunat_env',
                'logo_path'
            ]);
        });
    }
};
