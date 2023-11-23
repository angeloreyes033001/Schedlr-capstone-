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
        Schema::create('year_levels', function (Blueprint $table) {
            $table->bigIncrements('yearlevelID');
            $table->string("yearlevel",50)->nullable(false);
            $table->string("yearlevelDepartment",20);
            $table->boolean("yearlevelSoftDelete")->default(false);
            $table->foreign('yearlevelDepartment')->references('department')->on("departments")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('year_levels');
    }
};
