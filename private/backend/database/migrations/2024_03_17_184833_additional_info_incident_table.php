<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_additional_info', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Incident::class)->index();
            $table->string('recorded_by')->nullable();
            $table->string('attacked_service')->nullable();
            $table->enum('attack_severity', ['low', 'medium', 'high'])->default('low');
            $table->enum('predicated_attack_severity', ['low', 'medium', 'high'])->default('low');
            $table->text('attack_description')->nullable();
            $table->string('attack_category')->nullable();
            $table->string('attack_type')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->text('description_of_damage')->nullable();
            $table->dateTime('incident_created_at')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'incident_taken_by')->nullable()->index();
            $table->foreignIdFor(\App\Models\User::class, 'incident_approved_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_additional_info');

    }
};
