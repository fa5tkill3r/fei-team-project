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
            $table->text('attack_solution')->nullable();
            $table->text('attack_description')->nullable();
            $table->text('attack_vector')->nullable();
            $table->string('attack_category')->nullable();
            $table->string('attack_type')->nullable();
            $table->text('description')->nullable();
            $table->text('security_measures')->nullable();
            $table->text('notes')->nullable();
            // TODO: pridat do komentara boolean ci je sucat solutionu
            //TODO: pridat many opatrieni, (tabulka v docu)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_additional_info');

    }
};
