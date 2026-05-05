<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $primaryKey = 'SubjectID';

    protected $fillable = ['SubjectName'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_subjects', 'SubjectID', 'CourseID');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'SubjectID', 'SubjectID');
    }
}
