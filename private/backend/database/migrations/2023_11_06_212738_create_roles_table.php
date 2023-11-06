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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('task_access')->default(false);
            $table->boolean('task_create')->default(false);
            $table->boolean('task_delete')->default(false);

            $table->boolean('user_access')->default(false);
            $table->boolean('user_add')->default(false);
            
            $table->boolean('role_access')->default(false);
            $table->boolean('role_add')->default(false);
            $table->boolean('role_delete')->default(false);

            $table->boolean('team_info')->default(false);
            
            $table->timestamps();
        });

        DB::table('roles')->insert([
            'name' => 'Admin',
            'slug' => 'admin',
            'task_access' => true,
            'task_create' => true,
            'task_delete' => true,
            'user_access' => true,
            'user_add' => true,
            'role_access' => true,
            'role_add' => true,
            'role_delete' => true,
            'team_info' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
