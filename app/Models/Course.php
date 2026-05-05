<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'CourseID';

    protected $fillable = [
        'Title', 'Description', 'CategoryID', 'AdminID', 'Price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'AdminID', 'UserID');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'CourseID', 'CourseID');
    }

    // Both singular and plural aliases for withCount compatibility
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'CourseID', 'CourseID');
    }

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class, 'CourseID', 'CourseID');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'course_subjects', 'CourseID', 'SubjectID');
    }
}
