<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
         // ── 4 Roles ───────────────────────────────────
         $roles = [
             ['RoleName' => 'Super Admin'],
             ['RoleName' => 'Admin'],
             ['RoleName' => 'Tutor'],
             ['RoleName' => 'Student'],
         ];
         foreach ($roles as $role) {
             \App\Models\Role::firstOrCreate($role);
         }

         // ── 4 Users (1 per role) ──────────────────────
         $superAdmin = \App\Models\User::firstOrCreate(
             ['Email' => 'superadmin@studyhub.com'],
             [
                'Name' => 'Juan Dela Cruz',
                'Password' => Hash::make('password'),
                'RoleID' => 1,
                'Status' => 'Active'
             ]
         );

         $admin = \App\Models\User::firstOrCreate(
             ['Email' => 'admin@studyhub.com'],
             [
                'Name' => 'Maria Santos',
                'Password' => Hash::make('password'),
                'RoleID' => 2,
                'Status' => 'Active'
             ]
         );

         $tutor = \App\Models\User::firstOrCreate(
             ['Email' => 'tutor@studyhub.com'],
             [
                'Name' => 'Jose Rizal',
                'Password' => Hash::make('password'),
                'RoleID' => 3,
                'Status' => 'Active'
             ]
         );

         $student = \App\Models\User::firstOrCreate(
             ['Email' => 'student@studyhub.com'],
             [
                'Name' => 'Ana Reyes',
                'Password' => Hash::make('password'),
                'RoleID' => 4,
                'Status' => 'Active'
             ]
         );

         // ── 1 Category ────────────────────────────────
         $category = \App\Models\Category::firstOrCreate(['CategoryName' => 'Information Technology']);

         // ── 1 Subject ─────────────────────────────────
         $subject = \App\Models\Subject::firstOrCreate(['SubjectName' => 'Web Development']);

         // ── 1 Course ──────────────────────────────────
         $course = \App\Models\Course::firstOrCreate(
             ['Title' => 'Introduction to Web Development'],
             [
                'CategoryID' => $category->CategoryID,
                'AdminID' => $admin->UserID,
                'Price' => 499.00
             ]
         );

         // ── 1 Module (assigned to tutor) ──────────────
         $module = \App\Models\Module::firstOrCreate(
             ['ModuleTitle' => 'HTML, CSS & JavaScript Basics'],
             [
                'CourseID' => $course->CourseID,
                'TutorID' => $tutor->UserID,
             ]
         );

         // ── 3 Lessons ─────────────────────────────────
         \App\Models\Lesson::firstOrCreate(
             ['URL' => 'https://studyhub.com/lessons/html-fundamentals'],
             [
                'ModuleID' => $module->ModuleID,
                'ContentType' => 'Video',
                'TutorID' => $tutor->UserID
             ]
         );

         \App\Models\Lesson::firstOrCreate(
             ['URL' => 'https://studyhub.com/lessons/css-layout-guide.pdf'],
             [
                'ModuleID' => $module->ModuleID,
                'ContentType' => 'PDF',
                'TutorID' => $tutor->UserID
             ]
         );

         \App\Models\Lesson::firstOrCreate(
             ['URL' => 'https://studyhub.com/lessons/javascript-intro'],
             [
                'ModuleID' => $module->ModuleID,
                'ContentType' => 'Video',
                'TutorID' => $tutor->UserID
             ]
         );
    }
}
