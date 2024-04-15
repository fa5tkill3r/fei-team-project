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
        Schema::create('incident_chronologically', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->dateTime('date');
            $table->foreignIdFor(\App\Models\AdditionalIncidentInfo::class)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_chronologically');
    }
};
