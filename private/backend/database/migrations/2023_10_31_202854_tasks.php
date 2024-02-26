<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('deadline')->nullable();
            $table->text('description')->nullable();
            $table->string('severity');
            $table->foreignId('created_by_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->onDelete('cascade');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('incident_id')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->text('resolution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
