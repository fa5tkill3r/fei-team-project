<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('incident_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('incident_categories')->insert([
            ['name' => 'Útok'],
            ['name' => 'Zneužitie'],
            ['name' => 'Odcudzenie'],
            ['name' => 'Zlyhanie ľudského faktora'],
            ['name' => 'Vplyv zmien Čo to znamená?'],
            ['name' => 'Prerušenie prevádzky IS/SW'],
            ['name' => 'Nesprávna konfigurácia zariadení'],
        ]);


    }

    public function down(): void
    {
        Schema::dropIfExists('incident_categories');
    }
};
