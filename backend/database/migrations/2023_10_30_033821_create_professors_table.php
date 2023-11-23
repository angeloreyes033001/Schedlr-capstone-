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
        Schema::create('professors', function (Blueprint $table) {
            $table->string('professorID',20)->primary();
            $table->string('professorFullname',50);
            $table->string('professorStatus',20)->default("regular");
            $table->unsignedBigInteger("professorRank");
            $table->string('professorDesignation',50);
            $table->string("professorDepartment",20);
            $table->tinyInteger("professorSoftDelete")->default(0);
            $table->foreign('professorRank')->references('rankID')->on('ranks')->onDelete('cascade');
            $table->foreign('professorDepartment')->references('department')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professors');
    }
};
