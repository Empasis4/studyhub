<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSubjectsTable extends Migration
{
    public function up()
    {
        Schema::create('course_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('CourseID');
            $table->unsignedBigInteger('SubjectID');
            $table->timestamps();

            $table->primary(['CourseID', 'SubjectID']);
            $table->foreign('CourseID')->references('CourseID')->on('courses')->onDelete('cascade');
            $table->foreign('SubjectID')->references('SubjectID')->on('subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_subjects');
    }
}
