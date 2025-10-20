<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('catalogos', function (Blueprint $table) {
            $table->id();

            // Nombre visible en la UI
            $table->string('nombre');

            // Identificador técnico para referenciar desde código (único)
            $table->string('slug')->unique();

            // Opcional: breve descripción del catálogo (para ayuda o tooltips)
            $table->string('descripcion')->nullable();

            // Opcional: clase de icono/emoji para la UI (ej: "bi bi-tag" o "mdi mdi-office")
            $table->string('icono')->nullable();

            // Orden opcional para listar catálogos
            $table->integer('orden')->nullable();

            // Habilitado/visible en la UI
            $table->boolean('activo')->default(true);

            $table->timestamps();
            $table->softDeletes(); // para no perder historiales
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogos');
    }
};
