<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id('CourseID');
            $table->string('Title');
            $table->text('Description')->nullable();
            $table->unsignedBigInteger('CategoryID');
            $table->unsignedBigInteger('AdminID'); // UserID of Admin
            $table->decimal('Price', 10, 2);
            $table->timestamps();

            $table->foreign('CategoryID')->references('CategoryID')->on('categories')->onDelete('cascade');
            $table->foreign('AdminID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
