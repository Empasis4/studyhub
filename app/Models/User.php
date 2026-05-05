<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'UserID';

    protected $fillable = [
        'Name',
        'Email',
        'Password',
        'RoleID',
        'Status',
    ];

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'RoleID', 'RoleID');
    }

    public function managedCourses()
    {
        return $this->hasMany(Course::class, 'AdminID', 'UserID');
    }

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class, 'StudentID', 'UserID');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'StudentID', 'UserID');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'UserID', 'UserID');
    }

    public function systemLogs()
    {
        return $this->hasMany(SystemLog::class, 'SuperAdminID', 'UserID');
    }
}
