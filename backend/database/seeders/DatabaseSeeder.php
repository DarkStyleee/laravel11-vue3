<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Запустить сидеры.
     *
     * @return void
     */
    public function run()
    {
        // Вы можете изменить порядок, если необходимо
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            AccessGroupSeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
