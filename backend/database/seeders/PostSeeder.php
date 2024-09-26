<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Запустить сидер.
     *
     * @return void
     */
    public function run()
    {
        // Убедитесь, что у вас есть пользователи для связи с постами
        $users = User::all();

        if ($users->count() === 0) {
            $this->command->info('Нет пользователей, создаем фиктивных пользователей.');
            User::factory()->count(10)->create();
            $users = User::all();
        }

        // Создаем 50 постов
        Post::factory()->count(50)->make()->each(function ($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });

        $this->command->info('Посты успешно созданы.');
    }
}
