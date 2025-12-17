<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class SunatDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Toma la primera empresa demo y le agrega credenciales de pruebas MODDATOS.
        $company = Company::first();
        if (!$company) {
            return;
        }

        $company->update([
            'sunat_env' => 'beta',
            'sunat_sol_user' => 'MODDATOS',
            'sunat_sol_password' => Crypt::encryptString('MODDATOS'),
            'sunat_cert_path' => 'certificates/demo-cert.pfx', // coloca aquí tu .pfx real
            'sunat_cert_password' => Crypt::encryptString('MODDATOS'),
        ]);
    }
}
