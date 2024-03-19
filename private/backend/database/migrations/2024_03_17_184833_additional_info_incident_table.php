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
            $table->string('attacked_service')->nullable();
            $table->enum('attack_severity', ['low', 'medium', 'high'])->default('low');
            $table->text('attack_solution')->nullable();
            $table->text('attack_description')->nullable();
            $table->text('attack_vector')->nullable();
            //  zmenit na classu enum dynamicky zmenit a je to v stpcoch nie tak ako som si myslel
            // nechat tu iba string na FE natiahnut z enumu stringy a podla toho upravit aj category aj type
            $table->enum('attack_category', ['attack', 'abuse', 'fraud', 'missusage'])->default('attack');
            $table->string('attack_type')->nullable();
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
