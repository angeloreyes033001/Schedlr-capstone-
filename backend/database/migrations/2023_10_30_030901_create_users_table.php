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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('userID');
            $table->string("userEmail",50);
            $table->string('userFullname',50);
            $table->string('userPassword',100);
            $table->string("userRole", 10)->default("admin");
            $table->string('userDepartment',20);
            $table->boolean('userSoftDelete')->default(false);
            $table->string("personal_tokens",100);
            $table->foreign('userDepartment')->references('department')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
