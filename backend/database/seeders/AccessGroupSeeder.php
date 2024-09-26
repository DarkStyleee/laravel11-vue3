<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccessGroup;

class AccessGroupSeeder extends Seeder
{
    /**
     * Запустить сидер.
     *
     * @return void
     */
    public function run()
    {
        AccessGroup::create(['name' => 'reader']);
        AccessGroup::create(['name' => 'editor']);
        AccessGroup::create(['name' => 'moderator']);
        AccessGroup::create(['name' => 'administrator']);

        $this->command->info('Группы доступа успешно созданы.');
    }
}