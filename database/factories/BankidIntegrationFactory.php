<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\BankidIntegration;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankidIntegrationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankidIntegration::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'label' => $this->faker->word,
            'description' => $this->faker->sentence(15),
            'active' => $this->faker->boolean,
            'pkcs' => $this->faker->text,
            'password' => $this->faker->password,
            'type' => 'pfx',
            'url_prefix' => $this->faker->text(255),
            'success_url' => $this->faker->text(255),
            'error_url' => $this->faker->text(255),
            'environment' => 'test',
            'layout' => [],
            'languages' => [],
            'extra_html' => $this->faker->text,
        ];
    }
}
