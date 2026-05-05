<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemLogsTable extends Migration
{
    public function up()
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id('LogID');
            $table->unsignedBigInteger('SuperAdminID');
            $table->string('Action');
            $table->string('IPAddress');
            $table->timestamp('Timestamp')->useCurrent();
            $table->timestamps();

            $table->foreign('SuperAdminID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_logs');
    }
}
