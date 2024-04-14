<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('incident_types')->insert([
            ['name' => 'Neautorizované činnosti v IKT'],
            ['name' => 'Infiltrácia, alebo pokus o zavedenie škodlivého kódu'],
            ['name' => 'Neoprávnený fyzický prístup'],
            ['name' => 'Zneužitie prístupových práv'],
            ['name' => 'Únik informácií'],
            ['name' => 'Neautorizované externé činnosti voči IKT'],

        ]);


    }

    public function down(): void
    {
        Schema::dropIfExists('incident_types');
    }
};
