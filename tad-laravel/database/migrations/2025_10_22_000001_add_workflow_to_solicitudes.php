<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('solicitudes', function (Blueprint $t) {
            $t->unsignedInteger('current_etapa_index')->default(0);
            $t->json('etapas_history')->nullable();
            $t->json('operadores_asignados')->nullable();
        });
    }
    public function down(): void {
        Schema::table('solicitudes', function (Blueprint $t) {
            $t->dropColumn(['current_etapa_index','etapas_history','operadores_asignados']);
        });
    }
};
?>