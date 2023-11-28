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
            $table->string("scheduleWork",10)->default("regular");// regular/overtime
            $table->string("scheduleProfessor",20);
            $table->string("scheduleSubject",20);
            $table->unsignedBigInteger("scheduleClassroom");
            $table->string("scheduleSection",50);
            $table->string("scheduleSemester",20)->default("1st semester");
            $table->boolean("scheduleStatus")->default(false);
            $table->boolean("scheduleApproved")->default(false);
            $table->string('scheduleAcademicYear',10);
            $table->boolean("scheduleSoftDelete")->default(false);
            $table->foreign('scheduleProfessor')->references("professorID")->on('professors')->onDelete('cascade');
            $table->foreign('scheduleSubject')->references("subjectCode")->on('subjects')->onDelete('cascade');
            $table->foreign('scheduleClassroom')->references("classroomID")->on('classrooms')->onDelete('cascade');
            $table->foreign('scheduleSection')->references("sectionopenedName")->on('section_openeds')->onDelete('cascade');
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
