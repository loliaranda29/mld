<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('catalogo_items');

        Schema::create('catalogo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalogo_id')->constrained('catalogos')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('codigo')->nullable();
            $table->integer('orden')->nullable();
            $table->boolean('activo')->default(true);
            $table->text('meta')->nullable(); // guardamos JSON como texto
            $table->timestamps();
            $table->softDeletes();

            $table->index(['catalogo_id','activo']);
            $table->index(['catalogo_id','orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogo_items');
    }
};
