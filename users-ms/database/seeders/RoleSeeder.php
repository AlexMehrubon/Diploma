<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Администратор',
            'code' => RoleEnum::ADMIN->value,
        ]);
        Role::create([
           'name' => 'Пользователь',
           'code' => RoleEnum::USER->value,
        ]);
    }
}
