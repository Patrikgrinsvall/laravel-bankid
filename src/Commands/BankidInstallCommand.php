<?php

namespace Patrikgrinsvall\LaravelBankid\Commands;

use Illuminate\Console\Command;

class BankidInstallCommand extends Command
{
    public $signature = 'bankid:install {stack=blade}';

    public $description = 'This command will publish and build correct stack';

    public function handle()
    {

    }
}
