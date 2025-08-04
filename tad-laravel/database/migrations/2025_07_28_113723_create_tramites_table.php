<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('publicado')->default(false);
            $table->boolean('disponible')->default(false);
            $table->boolean('mostrar_inicio')->default(false);
            $table->string('tipo')->nullable(); // ej: "online", "presencial", etc.
            $table->string('estatus')->nullable(); // estado inicial por defecto
            $table->json('etapas')->nullable(); // puede eliminarse más adelante si se normaliza
            $table->text('mensaje')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
};
