<?php

namespace Database\Factories;

use App\Modules\Fichas\Models\Course_group;
use App\Modules\Fichas\Models\CourseGroupTeacher;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseGroupTeacher>
 */
class CourseGroupTeacherFactory extends Factory
{
    protected $model = CourseGroupTeacher::class;

    public function definition(): array
    {
        return [
            'course_group_id' => Course_group::factory(),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
