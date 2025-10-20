<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tramite_id')
                ->constrained('tramites')
                ->cascadeOnDelete();

            // 👉 Sin referenciar la clase User: apuntamos directo a la tabla 'users'
            $table->foreignId('usuario_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('expediente', 64)->index();
            $table->string('estado', 32)->default('iniciado');
            $table->json('datos')->nullable(); // en SQL Server se mapea a NVARCHAR(MAX)
            $table->timestamps();

            $table->unique(['tramite_id', 'expediente']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
