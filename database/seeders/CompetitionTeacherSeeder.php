<?php

namespace Database\Seeders;

use App\Modules\Competencias\Models\Competition;
use App\Modules\Competencias\Models\CompetitionTeacher;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Seeder;

class CompetitionTeacherSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([CompetitionSeeder::class, TeacherSeeder::class]);

        $competencias = Competition::orderBy('id')->get();
        $teachers = Teacher::orderBy('id')->get();

        foreach ($competencias as $index => $competencia) {
            $teacher = $competencia->teacher_id ?: $teachers[$index % $teachers->count()]->id;
            CompetitionTeacher::updateOrCreate(
                ['competency_id' => $competencia->id, 'teacher_id' => $teacher],
                []
            );
        }
    }
}
