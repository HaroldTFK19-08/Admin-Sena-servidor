<?php

namespace App\Modules\Fichas\Models;

use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseGroupTeacher extends Model
{
    use HasFactory;

    protected $table = 'course_group_teacher';

    protected $fillable = [
        'course_group_id',
        'teacher_id',
    ];

    public function courseGroup()
    {
        return $this->belongsTo(Course_group::class, 'course_group_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
