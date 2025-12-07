<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\PlanFeature;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Básico',
                'price_monthly' => 50.00,
                'price_yearly' => 500.00,
                'description' => 'Para pequeños negocios.',
                'features' => [
                    'max_users' => 2,
                    'max_invoices' => 100,
                    'storage_gb' => 1
                ]
            ],
            [
                'name' => 'Estándar',
                'price_monthly' => 120.00,
                'price_yearly' => 1200.00,
                'description' => 'El más popular.',
                'features' => [
                    'max_users' => 5,
                    'max_invoices' => 500,
                    'storage_gb' => 5
                ]
            ],
            [
                'name' => 'Premium',
                'price_monthly' => 250.00,
                'price_yearly' => 2500.00,
                'description' => 'Sin límites para grandes empresas.',
                'features' => [
                    'max_users' => 20,
                    'max_invoices' => 10000,
                    'storage_gb' => 20
                ]
            ]
        ];

        foreach ($plans as $data) {
            $plan = Plan::firstOrCreate(
                ['name' => $data['name']],
                [
                    'price_monthly' => $data['price_monthly'],
                    'price_yearly' => $data['price_yearly'],
                    'description' => $data['description']
                ]
            );

            foreach ($data['features'] as $key => $value) {
                PlanFeature::firstOrCreate(
                    ['plan_id' => $plan->id, 'key' => $key],
                    ['value' => $value]
                );
            }
        }
        
        $this->command->info('Planes creados correctamente.');
    }
}
