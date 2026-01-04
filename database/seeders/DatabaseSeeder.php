<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Run strict seeders (Roles, Permisos, Types, etc.)
        $this->call([
            PrincipalRolesSeeder::class,
            PrincipalPermisosSeeder::class,
            PrincipalRolPermisoSeeder::class,
            TiposServicioSeeder::class,
            CitaEstadosSeeder::class,
            CategoriasInventarioSeeder::class,
            UnidadesInventarioSeeder::class,
        ]);

        // 2. Create Test User (Admin)
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // 3. Create Persona linked to User
        $persona = \App\Models\Principal\Persona::create([
            'persona_id' => \Illuminate\Support\Str::uuid(),
            'tipo_persona' => 'ADMINISTRATIVO', // From PrincipalRolesSeeder implication
            'nombres' => 'Admin',
            'apellidos' => 'User',
            'email' => 'admin@example.com',
            'dni' => '00000000',
        ]);

        // 4. Create Usuario linked to User and Persona
        \App\Models\Principal\Usuario::create([
            'user_id' => $user->id,
            'persona_id' => $persona->persona_id,
            'username' => 'admin',
            'rol_id' => 1, // ADMINISTRADOR
            'activo' => true,
        ]);
    }
}
