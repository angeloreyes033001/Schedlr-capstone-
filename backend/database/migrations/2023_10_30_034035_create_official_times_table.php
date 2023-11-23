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
        Schema::create('official_times', function (Blueprint $table) {
            $table->bigIncrements('officialtimeID');
            $table->string("officialtimeDay",10);
            $table->tinyInteger("officialtimeStart");
            $table->tinyInteger("officialtimeEnd");
            $table->string("officialtimeProfessor",20);
            $table->string("officialtimeSemester",20)->default("1st semester");
            $table->boolean('officialtimeSoftDelete')->default(0);
            $table->foreign('officialtimeProfessor')->references("professorID")->on('professors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('official_times');
    }
};
