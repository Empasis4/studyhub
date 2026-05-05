<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentsTable extends Migration
{
    public function up()
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id('AssessmentID');
            $table->unsignedBigInteger('LessonID');
            $table->date('DueDate');
            $table->integer('TotalScore');
            $table->text('Instructions')->nullable();
            $table->timestamps();

            $table->foreign('LessonID')->references('LessonID')->on('lessons')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assessments');
    }
}
