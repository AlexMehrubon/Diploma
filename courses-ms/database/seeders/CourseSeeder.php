<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\DifficultyLevel;
use App\Models\Status;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Программирование'],
            ['name' => 'Дизайн'],
            ['name' => 'Маркетинг'],
            ['name' => 'Аналитика'],
            ['name' => 'Для начинающих'],
            ['name' => 'Продвинутый уровень'],
            ['name' => 'Веб-разработка'],
            ['name' => 'Мобильная разработка'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag['name']], $tag);
        }

        // Получаем ID уровней сложности и статусов
        $beginnerLevel = DifficultyLevel::where('name', 'Начальный')->first();
        $intermediateLevel = DifficultyLevel::where('name', 'Средний')->first();
        $advancedLevel = DifficultyLevel::where('name', 'Продвинутый')->first();

        $activeStatus = Status::where('name', 'Активный')->first();
        $draftStatus = Status::where('name', 'В разработке')->first();

        // Создаем курсы
        $courses = [
            [
                'name' => 'Основы PHP',
                'description' => 'Изучите основы PHP для веб-разработки',
                'duration' => 30,
                'rating' => 4.5,
                'about' => 'о курсе',
                'status_id' => $activeStatus->id,
                'difficulty_level_id' => $beginnerLevel->id,
                'tags' => ['Программирование', 'Для начинающих', 'Веб-разработка']
            ],
            [
                'name' => 'Laravel для профессионалов',
                'description' => 'Продвинутые техники работы с Laravel',
                'duration' => 45,
                'about' => 'о курсе',
                'rating' => 4.8,
                'status_id' => $activeStatus->id,
                'difficulty_level_id' => $advancedLevel->id,
                'tags' => ['Программирование', 'Продвинутый уровень', 'Веб-разработка']
            ],
            [
                'name' => 'UI/UX дизайн',
                'description' => 'Основы пользовательского интерфейса и опыта',
                'duration' => 25,
                'about' => 'о курсе',
                'rating' => 4.7,
                'status_id' => $activeStatus->id,
                'difficulty_level_id' => $intermediateLevel->id,
                'tags' => ['Дизайн', 'Для начинающих']
            ],
            [
                'name' => 'Digital маркетинг',
                'description' => 'Стратегии продвижения в интернете',
                'duration' => 35,
                'about' => 'о курсе',
                'rating' => 4.3,
                'status_id' => $activeStatus->id,
                'difficulty_level_id' => $intermediateLevel->id,
                'tags' => ['Маркетинг']
            ],
            [
                'name' => 'React Native',
                'description' => 'Разработка мобильных приложений на React Native',
                'duration' => 40,
                'about' => 'о курсе',
                'rating' => 4.6,
                'status_id' => $draftStatus->id,
                'difficulty_level_id' => $advancedLevel->id,
                'tags' => ['Программирование', 'Продвинутый уровень', 'Мобильная разработка']
            ],
        ];

        foreach ($courses as $courseData) {
            $tags = $courseData['tags'];
            unset($courseData['tags']);

            $course = Course::firstOrCreate(['name' => $courseData['name']], $courseData);

            // Привязываем теги к курсу
            $tagIds = Tag::whereIn('name', $tags)->pluck('id')->toArray();
            $course->tags()->syncWithoutDetaching($tagIds);
        }
    }
}
