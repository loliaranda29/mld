<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('requerimientos', function (Blueprint $table) {
      $table->id();

      // Relación con trámite y con la instancia/caso (ajustá el nombre de tu tabla de casos)
      $table->foreignId('tramite_id')->constrained('tramites');
      $table->foreignId('instancia_id')->constrained('instancias'); // o 'expedientes' según tu modelo

      // Plantilla (sección activable) y su snapshot
      $table->string('section_key')->nullable();     // si querés claves únicas por sección
      $table->string('section_name');                // "Documentación adicional", etc.
      $table->json('form_schema');                   // snapshot de la sección (fields, etc.)

      // Estado del requerimiento y datos de gestión
      $table->enum('estado', ['pendiente','respondido','vencido','cancelado'])->default('pendiente');
      $table->timestamp('fecha_limite')->nullable();
      $table->text('mensaje_funcionario')->nullable();

      // Respuestas del ciudadano/a
      $table->json('respuestas_json')->nullable();
      $table->timestamp('respondido_at')->nullable();

      // Quién lo creó y a quién se dirige
      $table->foreignId('creado_por')->constrained('users');   // funcionario/a
      $table->foreignId('dirigido_a')->nullable()->constrained('users'); // ciudadano/a, si lo manejás así

      $table->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('requerimientos');
  }
};
