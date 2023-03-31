<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TruffleFactory extends Factory
{
    public function definition()
    {
        return [
            'sku' => Str::uuid(),
            'weight' => fake()->unique()->numberBetween(1, 9999999),
            'price' => fake()->randomFloat(),
            'created_at' => now(),
            'expires_at' => now()->modify('+1 month')
        ];
    }
}
