<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requerimientos', function (Blueprint $table) {
            $table->id();

            // Trámite origen (plantilla de donde sale la sección activable)
            $table->foreignId('tramite_id')->constrained('tramites'); // NO ACTION por default en SQL Server

            // DESTINO POLIMÓRFICO (evita FK a tablas que quizá no existen todavía)
            // Ej.: target_type = 'App\\Models\\Instancia' o 'expediente', etc.
            $table->string('target_type');
            $table->unsignedBigInteger('target_id');
            $table->index(['target_type', 'target_id']);

            // Metadatos
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();

            // Snapshot de la sección activable (esquema que el ciudadano debe completar)
            $table->json('seccion_schema');

            // Respuestas del ciudadano/adjuntos (cuando responda el requerimiento)
            $table->json('datos')->nullable();

            // Estados del requerimiento
            $table->string('estado')->default('pendiente'); // pendiente | enviado | respondido | resuelto
            $table->timestamp('vence_at')->nullable();

            // Trazabilidad
            $table->foreignId('created_by')->nullable()->constrained('users');   // quién lo emitió
            $table->foreignId('responded_by')->nullable()->constrained('users'); // quién marcó como respondido/resuelto

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requerimientos');
    }
};
