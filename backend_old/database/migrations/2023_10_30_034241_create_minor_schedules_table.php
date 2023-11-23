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
        Schema::create('minor_schedules', function (Blueprint $table) {
            $table->bigIncrements("minorID");
            $table->string("minorDay",10);
            $table->tinyInteger("minorStart");
            $table->tinyInteger("minorEnd");
            // $table->tinyInteger("minorReview")->default(0);
            $table->string("minorStatus",10)->default("regular");
            $table->string("minorProfessor",20);
            $table->string("minorSubject",20);
            $table->unsignedBigInteger("minorClassroom");
            $table->unsignedBigInteger("minorSection");
            $table->string("minorSemester",20)->default("1st semester");
            $table->string('minorDepartment');
            $table->boolean("minorSoftDelete")->default(false);
            $table->foreign('minorProfessor')->references("professorID")->on('professors')->onDelete('cascade');
            $table->foreign('minorSubject')->references("subjectCode")->on('subjects')->onDelete('cascade');
            $table->foreign('minorClassroom')->references("classroomID")->on('classrooms')->onDelete('cascade');
            $table->foreign('minorSection')->references("sectionID")->on('sections')->onDelete('cascade');
            $table->foreign('minorDepartment')->references('department')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minor_schedules');
    }
};
