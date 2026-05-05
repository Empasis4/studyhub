<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id('LessonID');
            $table->unsignedBigInteger('ModuleID');
            $table->string('ContentType'); // Video | Text
            $table->string('URL');
            $table->unsignedBigInteger('TutorID');
            $table->timestamps();

            $table->foreign('ModuleID')->references('ModuleID')->on('modules')->onDelete('cascade');
            $table->foreign('TutorID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
