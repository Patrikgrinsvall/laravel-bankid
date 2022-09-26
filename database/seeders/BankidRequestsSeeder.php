<?php

namespace Database\Seeders;

use App\Models\BankidRequests;
use Illuminate\Database\Seeder;

class BankidRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankidRequests::factory()
            ->count(5)
            ->create();
    }
}
