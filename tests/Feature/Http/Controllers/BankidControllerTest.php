<?php

namespace Patrikgrinsvall\LaravelBankid\Tests\Feature\Http\Controllers;

use Patrikgrinsvall\LaravelBankid\Tests\TestCase;

class BankidControllerTest extends TestCase
{

    /** @test */
    public function the_controller_returns_ok()
    {
        $this->get('/member/login')->assertOk();
    }
}
