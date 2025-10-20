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
    Schema::create('citas_configuraciones', function (Blueprint $table) {
      $table->id();

      // 🔗 Relación con trámites
      $table->unsignedBigInteger('tramite_id');
      $table->unique('tramite_id');
      $table->foreign('tramite_id')
        ->references('id')
        ->on('tramites')
        ->onDelete('cascade');

      // 📅 Período
      $table->date('fecha_inicio');
      $table->date('fecha_fin');
      $table->boolean('todo_el_anio')->default(false);

      // 🗓️ Días de atención (guardados como texto: "Lunes,Martes,…")
      $table->text('dias_atencion');

      // ⏰ Horarios principales
      $table->time('hora_inicio');
      $table->time('hora_fin');

      // ⏰ Horarios secundarios (opcional)
      $table->boolean('dividir_horario')->default(false);
      $table->time('hora_inicio_2')->nullable();
      $table->time('hora_fin_2')->nullable();

      // ⚙️ Configuración
      $table->integer('duracion_bloque');     // minutos por bloque
      $table->integer('cupo_por_bloque');     // cupos diarios calculados
      $table->enum('estado', ['activo', 'inactivo'])->default('activo');

      // 🔍 Auditoría básica
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('citas_configuraciones');
  }
};
