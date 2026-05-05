<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id('ModuleID');
            $table->unsignedBigInteger('CourseID');
            $table->unsignedBigInteger('TutorID');
            $table->string('ModuleTitle');
            $table->timestamps();

            $table->foreign('CourseID')->references('CourseID')->on('courses')->onDelete('cascade');
            $table->foreign('TutorID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
