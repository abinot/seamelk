<?php

namespace Modules\RealEstate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RealEstateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\RealEstate\Models\RealEstate::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

