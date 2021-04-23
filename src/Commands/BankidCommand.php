<?php

namespace Patrikgrinsvall\LaravelBankid\Commands;

use Illuminate\Console\Command;

class BankidCommand extends Command
{
    public $signature = 'bankid';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
