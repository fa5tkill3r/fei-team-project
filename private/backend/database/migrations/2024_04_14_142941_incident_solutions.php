<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_solutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('name_of_responsible_person');
            $table->DateTime('deadline');
            $table->foreignIdFor(\App\Models\AdditionalIncidentInfo::class)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_solutions');
    }
};
