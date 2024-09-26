<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentSeeder extends Seeder
{
    /**
     * Запустить сидер.
     *
     * @return void
     */
    public function run()
    {
        // Убедитесь, что у вас есть посты и пользователи для связи с комментариями
        $posts = Post::all();
        $users = User::all();

        if ($posts->count() === 0) {
            $this->command->info('Нет постов, создаем фиктивные посты.');
            Post::factory()->count(50)->create();
            $posts = Post::all();
        }

        if ($users->count() === 0) {
            $this->command->info('Нет пользователей, создаем фиктивных пользователей.');
            User::factory()->count(10)->create();
            $users = User::all();
        }

        // Создаем 200 комментариев
        Comment::factory()->count(200)->make()->each(function ($comment) use ($users, $posts) {
            $comment->user_id = $users->random()->id;
            $comment->post_id = $posts->random()->id;
            $comment->save();
        });

        $this->command->info('Комментарии успешно созданы.');
    }
}
