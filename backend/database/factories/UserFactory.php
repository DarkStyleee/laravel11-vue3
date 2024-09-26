<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\AccessGroup;
use App\Models\User;
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * Определить модель.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Устанавливаем пароль для всех пользователей
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Настроить модель.
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Назначение роли по умолчанию
            $role = Role::where('name', 'user')->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }

            // Назначение группы доступов по умолчанию
            $accessGroup = AccessGroup::where('name', 'group1')->first();
            if ($accessGroup) {
                $user->accessGroups()->attach($accessGroup->id);
            }
        });
    }

    /**
     * Состояние модели для не верифицированных пользователей.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
