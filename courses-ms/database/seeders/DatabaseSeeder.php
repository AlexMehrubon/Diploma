<?php

namespace Database\Seeders;

use App\Models\DifficultyLevel;
use App\Models\Status;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {



        DifficultyLevel::create([
            'name' => 'Начальный'
        ]);
        DifficultyLevel::create([
            'name' => 'Средний'
        ]);
        DifficultyLevel::create([
            'name' => 'Продвинутый'
        ]);

        Status::create([
           'name' => 'Активный'
        ]);
        Status::create([
            'name' => 'Приостановлен'
        ]);
        Status::create([
            'name' => 'В разработке'
        ]);


        (new CourseSeeder())->run();
    }
}
