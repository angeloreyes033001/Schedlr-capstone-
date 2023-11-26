<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->bigIncrements('classroomID');
            $table->string("classroomName",50);
            $table->string("classroomType",10)->default("lecture");
            $table->boolean("classroomMulti")->default(0);
            // $table->unsignedBigInteger('classroomYearlevel');
            $table->string("classroomDepartment",20);
            $table->boolean("classroomSoftDelete")->default(false);
            // $table->foreign('classroomYearlevel')->references('yearlevelID')->on('year_levels')->onDelete("cascade");
            $table->foreign('classroomDepartment')->references('department')->on('departments')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
