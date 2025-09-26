<?php

namespace ModuleMyProcurement\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->call('module:migrate', ['module' => 'MyProcurement']);
        
        $this->call(MyProcurementBaseSeeder::class);
        $this->call(MyProcurementDataSeeder::class);
        $this->call(MyProcurementUserSeeder::class);
    }
}
