<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CatalogSeeder::class,
            RoleAuthSeeder::class,
            DemoSeeder::class,
            AdminConorldSeeder::class,
            InventorySeeder::class,
            SalesSettingsSeeder::class,
        ]);
    }
}
