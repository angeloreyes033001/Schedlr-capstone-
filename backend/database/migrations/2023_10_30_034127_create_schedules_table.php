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
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements("scheduleID");
            $table->string("scheduleDay",10);
            $table->tinyInteger("scheduleStart");
            $table->tinyInteger("scheduleEnd");
            // $table->tinyInteger("scheduleReview")->default(0);
            $table->string("scheduleStatus",10)->default("regular");
            $table->string("scheduleProfessor",20);
            $table->string("scheduleSubject",20);
            $table->unsignedBigInteger("scheduleClassroom");
            $table->unsignedBigInteger("scheduleSection");
            $table->string("scheduleSemester",20)->default("1st semester");
            $table->boolean("scheduleSoftDelete")->default(false);
            $table->foreign('scheduleProfessor')->references("professorID")->on('professors')->onDelete('cascade');
            $table->foreign('scheduleSubject')->references("subjectCode")->on('subjects')->onDelete('cascade');
            $table->foreign('scheduleClassroom')->references("classroomID")->on('classrooms')->onDelete('cascade');
            $table->foreign('scheduleSection')->references("sectionID")->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
