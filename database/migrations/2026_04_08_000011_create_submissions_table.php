<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id('SubmissionID');
            $table->unsignedBigInteger('AssessmentID');
            $table->unsignedBigInteger('StudentID');
            $table->unsignedBigInteger('SubjectID');
            $table->string('FileURL');
            $table->integer('Grade')->nullable();
            $table->string('Feedback')->nullable();
            $table->timestamps();

            $table->foreign('AssessmentID')->references('AssessmentID')->on('assessments')->onDelete('cascade');
            $table->foreign('StudentID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('SubjectID')->references('SubjectID')->on('subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}
