<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Company;

class ContactSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) return;

        $clients = [
            ['name' => 'Juan Perez', 'tax_id' => '10123456781'],
            ['name' => 'Empresa constructora SAC', 'tax_id' => '20123456789'],
            ['name' => 'María López', 'tax_id' => '10876543210'],
            ['name' => 'Distribuidora del Centro', 'tax_id' => '20555555551'],
            ['name' => 'Carlos Sanchez', 'tax_id' => '10999999999'],
        ];

        foreach ($clients as $client) {
            Contact::updateOrCreate(
                ['company_id' => $company->id, 'tax_id' => $client['tax_id']],
                [
                    'name' => $client['name'],
                    'type' => strlen($client['tax_id']) == 11 && str_starts_with($client['tax_id'], '20') ? 'company' : 'person',
                    'active' => true
                ]
            );
        }
    }
}
