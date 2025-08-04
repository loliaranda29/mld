<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campo_tramites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->string('nombre'); // slug o identificador interno
            $table->string('etiqueta'); // etiqueta visible para el ciudadano
            $table->string('tipo'); // texto_corto, archivo, fecha, etc.
            $table->boolean('requerido')->default(false);
            $table->integer('orden')->default(0);
            $table->json('valores')->nullable(); // para listas desplegables u opciones múltiples
            $table->json('condicional')->nullable(); // para lógica condicional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campo_tramites');
    }
};
