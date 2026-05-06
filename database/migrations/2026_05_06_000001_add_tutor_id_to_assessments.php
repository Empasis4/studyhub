<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTutorIdToAssessments extends Migration
{
    public function up()
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->unsignedBigInteger('TutorID')->nullable()->after('LessonID');
            $table->foreign('TutorID')->references('UserID')->on('users')->onDelete('set null');
        });

        // Backfill existing assessments if any (optional but good practice)
        try {
            DB::statement("UPDATE assessments a JOIN lessons l ON a.LessonID = l.LessonID SET a.TutorID = l.TutorID");
        } catch (\Exception $e) {}
    }

    public function down()
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign(['TutorID']);
            $table->dropColumn('TutorID');
        });
    }
}
