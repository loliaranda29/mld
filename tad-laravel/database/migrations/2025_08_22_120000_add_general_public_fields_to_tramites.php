<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('tramites', function (Blueprint $table) {
            // Encabezado de ficha
            if (!Schema::hasColumn('tramites','tutorial_html'))       $table->longText('tutorial_html')->nullable();
            if (!Schema::hasColumn('tramites','modalidad'))           $table->string('modalidad', 50)->nullable(); // Presencial | Online | Presencial/Online
            if (!Schema::hasColumn('tramites','implica_costo'))       $table->string('implica_costo', 20)->nullable(); // Con costo | Sin costo
            if (!Schema::hasColumn('tramites','detalle_costo_html'))  $table->longText('detalle_costo_html')->nullable();
            if (!Schema::hasColumn('tramites','telefono_oficina'))    $table->string('telefono_oficina', 60)->nullable();
            if (!Schema::hasColumn('tramites','horario_atencion'))    $table->string('horario_atencion', 120)->nullable();

            // Selects (guardamos id + nombre por ahora; más adelante podemos normalizar a tablas)
            if (!Schema::hasColumn('tramites','dependencia_id'))      $table->unsignedBigInteger('dependencia_id')->nullable();
            if (!Schema::hasColumn('tramites','dependencia_nombre'))  $table->string('dependencia_nombre', 160)->nullable();

            if (!Schema::hasColumn('tramites','categoria_id'))        $table->unsignedBigInteger('categoria_id')->nullable();
            if (!Schema::hasColumn('tramites','categoria_nombre'))    $table->string('categoria_nombre', 120)->nullable();

            if (!Schema::hasColumn('tramites','oficina_id'))          $table->unsignedBigInteger('oficina_id')->nullable();
            if (!Schema::hasColumn('tramites','oficina_nombre'))      $table->string('oficina_nombre', 160)->nullable();

            if (!Schema::hasColumn('tramites','ubicacion_id'))        $table->unsignedBigInteger('ubicacion_id')->nullable();
            if (!Schema::hasColumn('tramites','ubicacion_nombre'))    $table->string('ubicacion_nombre', 200)->nullable();

            // Bloques descriptivos
            if (!Schema::hasColumn('tramites','descripcion_html'))    $table->longText('descripcion_html')->nullable();
            if (!Schema::hasColumn('tramites','requisitos_html'))     $table->longText('requisitos_html')->nullable();
            if (!Schema::hasColumn('tramites','pasos_html'))          $table->longText('pasos_html')->nullable();
        });
    }

    public function down(): void {
        Schema::table('tramites', function (Blueprint $table) {
            $cols = [
                'tutorial_html','modalidad','implica_costo','detalle_costo_html',
                'telefono_oficina','horario_atencion',
                'dependencia_id','dependencia_nombre',
                'categoria_id','categoria_nombre',
                'oficina_id','oficina_nombre',
                'ubicacion_id','ubicacion_nombre',
                'descripcion_html','requisitos_html','pasos_html',
            ];
            foreach ($cols as $c) if (Schema::hasColumn('tramites',$c)) $table->dropColumn($c);
        });
    }
};
