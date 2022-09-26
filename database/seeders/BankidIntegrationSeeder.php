<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankidIntegration;

class BankidIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankidIntegration::factory()
            ->count(5)
            ->create();
    }
}
