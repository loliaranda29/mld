<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // En SQL Server usamos NVARCHAR(MAX). COL_LENGTH evita duplicados.
        DB::statement("IF COL_LENGTH('tramites', 'general_json') IS NULL     ALTER TABLE tramites ADD general_json NVARCHAR(MAX) NULL;");
        DB::statement("IF COL_LENGTH('tramites', 'formulario_json') IS NULL  ALTER TABLE tramites ADD formulario_json NVARCHAR(MAX) NULL;");
        DB::statement("IF COL_LENGTH('tramites', 'etapas_json') IS NULL      ALTER TABLE tramites ADD etapas_json NVARCHAR(MAX) NULL;");
        DB::statement("IF COL_LENGTH('tramites', 'documento_json') IS NULL   ALTER TABLE tramites ADD documento_json NVARCHAR(MAX) NULL;");
        DB::statement("IF COL_LENGTH('tramites', 'config_json') IS NULL      ALTER TABLE tramites ADD config_json NVARCHAR(MAX) NULL;");
    }

    public function down(): void
    {
        DB::statement("IF COL_LENGTH('tramites', 'general_json') IS NOT NULL    ALTER TABLE tramites DROP COLUMN general_json;");
        DB::statement("IF COL_LENGTH('tramites', 'formulario_json') IS NOT NULL ALTER TABLE tramites DROP COLUMN formulario_json;");
        DB::statement("IF COL_LENGTH('tramites', 'etapas_json') IS NOT NULL     ALTER TABLE tramites DROP COLUMN etapas_json;");
        DB::statement("IF COL_LENGTH('tramites', 'documento_json') IS NOT NULL  ALTER TABLE tramites DROP COLUMN documento_json;");
        DB::statement("IF COL_LENGTH('tramites', 'config_json') IS NOT NULL     ALTER TABLE tramites DROP COLUMN config_json;");
    }
};
