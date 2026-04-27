<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'path'    => 'images/' . $this->faker->uuid . '.jpg',
            'user_id' => User::factory(),
        ];
    }
}
