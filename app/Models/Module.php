<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $table = 'modules';
    protected $primaryKey = 'ModuleID';

    protected $fillable = ['CourseID', 'TutorID', 'ModuleTitle'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'CourseID', 'CourseID');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'TutorID', 'UserID');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'ModuleID', 'ModuleID');
    }
}
