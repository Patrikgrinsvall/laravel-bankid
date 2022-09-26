<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\BankidRequests;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankidRequestsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankidRequests::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'response' => $this->faker->text,
            'orderRef' => $this->faker->text(255),
            'status' => $this->faker->word,
            'bankid_integration_id' => \App\Models\BankidIntegration::factory(),
        ];
    }
}
