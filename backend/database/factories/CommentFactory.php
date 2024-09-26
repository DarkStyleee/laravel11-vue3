<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Comment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Имя связанной модели.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Определение состояния модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence,
            'user_id' => null, // Заполняется в сидере
            'post_id' => null, // Заполняется в сидере
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
