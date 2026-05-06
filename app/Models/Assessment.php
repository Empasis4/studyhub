<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'assessments';
    protected $primaryKey = 'AssessmentID';

    protected $fillable = ['LessonID', 'TutorID', 'DueDate', 'TotalScore', 'Instructions', 'AttachmentURL'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'LessonID', 'LessonID');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'AssessmentID', 'AssessmentID');
    }
}
