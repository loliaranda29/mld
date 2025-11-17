<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('catalogo_items', function (Blueprint $table) {
            if (!Schema::hasColumn('catalogo_items', 'catalogo_id')) {
                $table->unsignedBigInteger('catalogo_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('catalogo_items', 'nombre')) {
                $table->string('nombre')->nullable()->after('catalogo_id');
            }
            if (!Schema::hasColumn('catalogo_items', 'codigo')) {
                $table->string('codigo')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('catalogo_items', 'orden')) {
                $table->integer('orden')->nullable()->after('codigo');
            }
            if (!Schema::hasColumn('catalogo_items', 'activo')) {
                $table->boolean('activo')->default(true)->after('orden');
            }
            if (!Schema::hasColumn('catalogo_items', 'meta')) {
                // En SQL Server guardamos JSON como texto
                $table->text('meta')->nullable()->after('activo');
            }
        });

        // Crear FK si no existe (silenciamos si ya está)
        try {
            Schema::table('catalogo_items', function (Blueprint $table) {
                $table->foreign('catalogo_id')
                      ->references('id')->on('catalogos')
                      ->cascadeOnDelete();
            });
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        try {
            Schema::table('catalogo_items', function (Blueprint $table) {
                $table->dropForeign(['catalogo_id']);
            });
        } catch (\Throwable $e) {}

        Schema::table('catalogo_items', function (Blueprint $table) {
            foreach (['catalogo_id','nombre','codigo','orden','activo','meta'] as $c) {
                if (Schema::hasColumn('catalogo_items', $c)) $table->dropColumn($c);
            }
        });
    }
};
