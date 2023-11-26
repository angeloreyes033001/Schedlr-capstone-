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
        Schema::create('section_openeds', function (Blueprint $table) {
            $table->unsignedBigInteger("sectionopenedID");
            $table->string("sectionopenedName");
            $table->unsignedBigInteger('sectionopenedSpecialization');
            $table->boolean("sectionopenedSoftDelete")->default(false);
            $table->foreign('sectionopenedID')->references("sectionID")->on('sections')->onDelete('cascade');
            $table->foreign('sectionopenedSpecialization')->references("specializationID")->on('specializations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_openeds');
    }
};
