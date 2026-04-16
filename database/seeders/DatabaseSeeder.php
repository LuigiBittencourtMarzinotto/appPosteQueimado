<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Registro;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'nome'     => 'Administrador',
            'email'    => 'admin@postequeimado.com',
            'password' => Hash::make('admin123'),
            'tipo'     => 'ADMIN',
        ]);

        // Usuário comum
        $user = User::create([
            'nome'     => 'João Silva',
            'email'    => 'joao@email.com',
            'password' => Hash::make('user123'),
            'tipo'     => 'COMUM',
        ]);

        // Registros de exemplo
        Registro::create([
            'titulo'         => 'Poste apagado ao lado do Igapó',
            'descricao'      => 'O poste da Rua A está completamente apagado há 3 dias, causando insegurança à noite.',
            'endereco_texto' => 'Rua A, 192',
            'lat'            => -25.4290,
            'lng'            => -49.2671,
            'status'         => 'PENDENTE',
            'id_user'        => $user->id,
        ]);

        Registro::create([
            'titulo'         => 'Lâmpada queimada na Avenida B',
            'descricao'      => 'Lâmpada queimada no poste em frente à Smartfit.',
            'endereco_texto' => 'Avenida B, 201',
            'lat'            => -25.4350,
            'lng'            => -49.2700,
            'status'         => 'EM_ANDAMENTO',
            'id_user'        => $user->id,
        ]);

        Registro::create([
            'titulo'         => 'Poste apagado na esquina',
            'descricao'      => 'Poste apagado na esquina da Avenida Brasil com a Avenida Rio de Janeiro.',
            'endereco_texto' => 'Avenida Brasil, 976',
            'lat'            => -25.4400,
            'lng'            => -49.2750,
            'status'         => 'RESOLVIDO',
            'id_user'        => $user->id,
        ]);
    }
}
