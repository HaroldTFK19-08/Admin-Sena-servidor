<?php

namespace Database\Seeders;

use App\Modules\Fichas\Models\Course_group;
use App\Modules\Fichas\Models\CourseGroupTeacher;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Seeder;

class CourseGroupTeacherSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CourseGroupSeeder::class);

        $fichas = Course_group::orderBy('id')->get();
        $teachers = Teacher::orderBy('id')->get();

        foreach ($fichas as $index => $ficha) {
            $teacher = $ficha->teacher_id ?: $teachers[$index % $teachers->count()]->id;
            CourseGroupTeacher::updateOrCreate(
                ['course_group_id' => $ficha->id, 'teacher_id' => $teacher],
                []
            );
        }
    }
}
