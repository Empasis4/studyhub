<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('UserID');
            $table->string('Name');
            $table->string('Email')->unique();
            $table->string('Password');
            $table->unsignedBigInteger('RoleID');
            $table->string('Status'); // Active | Inactive
            $table->timestamps();

            $table->foreign('RoleID')->references('RoleID')->on('roles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
