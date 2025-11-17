<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('folio_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites')->cascadeOnDelete();
            $table->string('scope', 32)->default('global'); // ej: 'global' o 'year:2025'
            $table->unsignedBigInteger('current')->default(0);
            $table->timestamps();

            $table->unique(['tramite_id','scope']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('folio_sequences');
    }
};
