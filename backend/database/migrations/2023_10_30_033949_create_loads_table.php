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
        Schema::create('loads', function (Blueprint $table) {
            $table->bigIncrements('loadID');
            $table->string("loadProfessor",20);
            $table->string("loadSubject",20);
            $table->string("loadDepartment",20);
            $table->boolean("loadSoftDelete")->default(false);
            $table->foreign('loadProfessor')->references("professorID")->on('professors')->onDelete('cascade');
            $table->foreign('loadSubject')->references("subjectCode")->on('subjects')->onDelete('cascade');
            $table->foreign('loadDepartment')->references("department")->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loads');
    }
};
