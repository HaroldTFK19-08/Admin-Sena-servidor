<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TrainingCenterSeeder::class,
            AreaSeeder::class,
            EnvironmentSeeder::class,
            ProgramSeeder::class,
            AdminSeeder::class,
            TeacherSeeder::class,
            CourseGroupSeeder::class,
            ApprenticeSeeder::class,
            AspirantSeeder::class,
            CompetitionSeeder::class,
            ResultSeeder::class,
            OfferSeeder::class,
            RegistrationSeeder::class,
            GradeSeeder::class,
            EquipmentSeeder::class,
            AssignmentSeeder::class,
            NewsSeeder::class,
            CompetitionTeacherSeeder::class,
            CourseGroupTeacherSeeder::class,
        ]);
    }
}
