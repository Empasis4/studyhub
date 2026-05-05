<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';
    protected $primaryKey = 'LessonID';

    protected $fillable = ['ModuleID', 'ContentType', 'URL', 'TutorID'];

    public function module()
    {
        return $this->belongsTo(Module::class, 'ModuleID', 'ModuleID');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'TutorID', 'UserID');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'LessonID', 'LessonID');
    }

    // Singular alias for convenience (get first assessment)
    public function assessment()
    {
        return $this->hasOne(Assessment::class, 'LessonID', 'LessonID');
    }
}
