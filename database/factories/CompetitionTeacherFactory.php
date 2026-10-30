<?php

namespace Database\Factories;

use App\Modules\Competencias\Models\Competition;
use App\Modules\Competencias\Models\CompetitionTeacher;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompetitionTeacher>
 */
class CompetitionTeacherFactory extends Factory
{
    protected $model = CompetitionTeacher::class;

    public function definition(): array
    {
        return [
            'competency_id' => Competition::factory(),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
