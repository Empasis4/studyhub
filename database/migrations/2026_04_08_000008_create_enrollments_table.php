<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id('EnrollmentID');
            $table->unsignedBigInteger('StudentID');
            $table->unsignedBigInteger('CourseID');
            $table->integer('ProgressPercentage')->default(0);
            $table->string('PaymentStatus'); // Paid | Pending
            $table->timestamps();

            $table->foreign('StudentID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('CourseID')->references('CourseID')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}
