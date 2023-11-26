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
        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('sectionID');
            $table->unsignedTinyInteger("sectionSlot");
            $table->string('sectionSemester',20)->default("1st semester");
            $table->unsignedBigInteger('sectionYearlevel');
            $table->string("sectionDepartment",20);
            $table->boolean("sectionSoftDelete")->default(false);
            $table->foreign('sectionYearlevel')->references("yearlevelID")->on('year_levels')->onDelete('cascade');
            $table->foreign('sectionDepartment')->references("department")->on("departments")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
