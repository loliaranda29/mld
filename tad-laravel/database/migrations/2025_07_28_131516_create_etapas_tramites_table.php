<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etapas_tramites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(0);
            //$table->foreignId('oficina_id')->nullable()->constrained('oficinas')->nullOnDelete(); // si no existe la tabla oficinas aún, se puede comentar temporalmente
            $table->boolean('requiere_firma')->default(false);
            $table->boolean('requiere_documentacion')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etapas_tramites');
    }
};
