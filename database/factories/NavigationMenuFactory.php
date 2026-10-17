<?php

namespace Database\Factories;

use App\Models\NavigationMenu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NavigationMenu>
 */
class NavigationMenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->word(),
            'url' => '/'.fake()->slug(1),
            'location' => fake()->randomElement(['header', 'footer', 'both']),
            'order' => fake()->numberBetween(1, 10),
            'target' => '_self',
            'is_active' => true,
        ];
    }
}
