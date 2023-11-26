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
        Schema::create('subjects', function (Blueprint $table) {
            $table->string("subjectCode",20)->primary();
            $table->text('subjectName');
            $table->string('subjectSemester',20)->default("1st semester");
            $table->unsignedBigInteger("subjectYearlevel");
            // $table->boolean("subjectLaboratory")->default(false);
            $table->integer('subjectLecHour');
            $table->integer('subjectLabHour');
            $table->unsignedBigInteger('subjectSpecialization');
            $table->string("subjectDepartment",20);
            $table->boolean("subjectSoftDelete")->default(false);
            $table->foreign('subjectYearlevel')->references("yearlevelID")->on('year_levels')->onDelete('cascade');
            $table->foreign('subjectSpecialization')->references("specializationID")->on('specializations')->onDelete('cascade');
            $table->foreign('subjectDepartment')->references("department")->on("departments")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
