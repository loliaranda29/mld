<?php
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class FuncionariosSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::updateOrCreate(
            ['email' => 'funcionario1@mld.local'],
            ['nombre' => 'Func 1', 'password' => Hash::make('secret123'), 'rol' => 'funcionario']
        );
        Usuario::updateOrCreate(
            ['email' => 'funcionario2@mld.local'],
            ['nombre' => 'Func 2', 'password' => Hash::make('secret123'), 'rol' => 'funcionario']
        );
    }
}
?>
