<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $table = 'submissions';
    protected $primaryKey = 'SubmissionID';

    protected $fillable = [
        'AssessmentID', 'StudentID', 'SubjectID', 'FileURL', 'Grade', 'Feedback'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'AssessmentID', 'AssessmentID');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'StudentID', 'UserID');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'SubjectID', 'SubjectID');
    }
}
