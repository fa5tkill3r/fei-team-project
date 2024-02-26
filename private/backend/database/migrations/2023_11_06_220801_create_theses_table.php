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
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('date');
            $table->longText('assignment');
            $table->longText('assignment_sk');
            $table->longText('abstract');
            $table->longText('abstract_sk');
            $table->string('leader');
            $table->string('students_name')->nullable();
            $table->string('path')->nullable();
            $table->string('tutors')->nullable();
            $table->longText('solution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theses');
    }
};
