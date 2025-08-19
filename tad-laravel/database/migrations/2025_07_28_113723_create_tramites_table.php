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
                $table->string('nombre'); // mejor que 'nombre', más claro en la UI
                $table->text('descripcion')->nullable();

                // switches generales
                $table->boolean('publicado')->default(false);
                $table->boolean('disponible')->default(false);
                $table->boolean('mostrar_inicio')->default(false);
                $table->boolean('acepta_solicitudes')->default(false);
                $table->boolean('acepta_pruebas')->default(false);
                $table->boolean('modulo_citas')->default(false);
                $table->boolean('modulo_inspectores')->default(false);

                // tipos y estados
                $table->string('tipo')->nullable();     // presencial / online
                $table->string('estatus')->nullable();  // estado inicial
                $table->text('mensaje')->nullable();    // mensaje al ciudadano

                // JSON por pestañas
                $table->json('general_json')->nullable();
                $table->json('formulario_json')->nullable();
                $table->json('etapas_json')->nullable();
                $table->json('documento_json')->nullable();
                $table->json('config_json')->nullable();

                $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
};
