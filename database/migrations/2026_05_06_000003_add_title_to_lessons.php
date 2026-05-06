<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleToLessons extends Migration
{
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('Title')->nullable()->after('ModuleID');
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('Title');
        });
    }
}
