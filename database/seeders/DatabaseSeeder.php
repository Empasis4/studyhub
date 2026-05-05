<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
         // ── 4 Roles ───────────────────────────────────
         \App\Models\Role::insert([
             ['RoleName' => 'Super Admin'],
             ['RoleName' => 'Admin'],
             ['RoleName' => 'Tutor'],
             ['RoleName' => 'Student'],
         ]);

         // ── 4 Users (1 per role) ──────────────────────
         $superAdmin = \App\Models\User::create([
             'Name' => 'Juan Dela Cruz',
             'Email' => 'super@studyhub.com',
             'Password' => Hash::make('password'),
             'RoleID' => 1,
             'Status' => 'Active'
         ]);

         $admin = \App\Models\User::create([
             'Name' => 'Maria Santos',
             'Email' => 'admin@studyhub.com',
             'Password' => Hash::make('password'),
             'RoleID' => 2,
             'Status' => 'Active'
         ]);

         $tutor = \App\Models\User::create([
             'Name' => 'Jose Rizal',
             'Email' => 'tutor@studyhub.com',
             'Password' => Hash::make('password'),
             'RoleID' => 3,
             'Status' => 'Active'
         ]);

         $student = \App\Models\User::create([
             'Name' => 'Ana Reyes',
             'Email' => 'student@studyhub.com',
             'Password' => Hash::make('password'),
             'RoleID' => 4,
             'Status' => 'Active'
         ]);

         // ── 1 Category ────────────────────────────────
         $category = \App\Models\Category::create(['CategoryName' => 'Information Technology']);

         // ── 1 Subject ─────────────────────────────────
         $subject = \App\Models\Subject::create(['SubjectName' => 'Web Development']);

         // ── 1 Course ──────────────────────────────────
         $course = \App\Models\Course::create([
             'Title' => 'Introduction to Web Development',
             'CategoryID' => $category->CategoryID,
             'AdminID' => $admin->UserID,
             'Price' => 499.00
         ]);

         // ── 1 Module (assigned to tutor) ──────────────
         $module = \App\Models\Module::create([
             'CourseID' => $course->CourseID,
             'TutorID' => $tutor->UserID,
             'ModuleTitle' => 'HTML, CSS & JavaScript Basics'
         ]);

         // ── 3 Lessons ─────────────────────────────────
         $lesson1 = \App\Models\Lesson::create([
             'ModuleID' => $module->ModuleID,
             'ContentType' => 'Video',
             'URL' => 'https://studyhub.com/lessons/html-fundamentals',
             'TutorID' => $tutor->UserID
         ]);

         $lesson2 = \App\Models\Lesson::create([
             'ModuleID' => $module->ModuleID,
             'ContentType' => 'PDF',
             'URL' => 'https://studyhub.com/lessons/css-layout-guide.pdf',
             'TutorID' => $tutor->UserID
         ]);

         $lesson3 = \App\Models\Lesson::create([
             'ModuleID' => $module->ModuleID,
             'ContentType' => 'Video',
             'URL' => 'https://studyhub.com/lessons/javascript-intro',
             'TutorID' => $tutor->UserID
         ]);

         // ── 1 Assessment (on Lesson 1) ────────────────
         $assessment = \App\Models\Assessment::create([
             'LessonID' => $lesson1->LessonID,
             'DueDate' => '2026-06-15',
             'TotalScore' => 100,
             'Instructions' => 'Create a simple HTML page that includes a header, a main section with a paragraph about yourself, and a footer with your contact information. Use at least 3 different HTML tags.'
         ]);

         // ── Student enrolled in the course (35% progress)
         \App\Models\Enrollment::create([
             'StudentID' => $student->UserID,
             'CourseID' => $course->CourseID,
             'ProgressPercentage' => 35,
             'PaymentStatus' => 'Paid'
         ]);

         // ── Student already submitted an answer ───────
         \App\Models\Submission::create([
             'AssessmentID' => $assessment->AssessmentID,
             'StudentID' => $student->UserID,
             'SubjectID' => $subject->SubjectID,
             'FileURL' => 'This is my HTML project. I created a responsive landing page using HTML5, CSS3 Flexbox, and JavaScript for form validation. The page includes a hero section, features grid, and a contact form.',
             'Grade' => null,
             'Feedback' => null,
         ]);

         // ── Notifications ─────────────────────────────
         // Student gets welcome
         \App\Models\Notification::create([
             'UserID' => $student->UserID,
             'Message' => 'Welcome to StudyHub! You are enrolled in "Introduction to Web Development".',
             'IsRead' => false
         ]);

         // Tutor gets submission alert
         \App\Models\Notification::create([
             'UserID' => $tutor->UserID,
             'Message' => 'Ana Reyes submitted an assessment for "Introduction to Web Development"',
             'IsRead' => false
         ]);

         // Admin gets enrollment alert
         \App\Models\Notification::create([
             'UserID' => $admin->UserID,
             'Message' => 'Ana Reyes enrolled in "Introduction to Web Development"',
             'IsRead' => false
         ]);

         // ── System Logs ───────────────────────────────
         \App\Models\SystemLog::create([
             'SuperAdminID' => $superAdmin->UserID,
             'Action' => 'System initialized — 4 users, 1 course seeded',
             'IPAddress' => '127.0.0.1',
             'Timestamp' => now()
         ]);
    }
}
