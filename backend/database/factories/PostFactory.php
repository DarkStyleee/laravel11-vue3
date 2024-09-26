<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;

class PostFactory extends Factory
{
    /**
     * Имя связанной модели.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Определение состояния модели.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraphs(3, true),
            'user_id' => null, // Заполняется в сидере
            'created_at' => now(),
            'updated_at' => now(),
            'views' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
