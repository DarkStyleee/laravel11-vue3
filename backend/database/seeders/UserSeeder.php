<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Запустить сидер.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create([
            'password' => Hash::make('password'), // Устанавливаем пароль для всех пользователей
        ]);

        $this->command->info('Пользователи успешно созданы.');
    }
}
