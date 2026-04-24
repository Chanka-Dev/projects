<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateAdminUserSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // --- Rol Admin: todos los permisos ---
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456')
        ]);
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::pluck('name')->all());
        $user->assignRole($adminRole);

        // --- Rol Chef: ver/crear/editar recetas + ver catálogos ---
        $chef = User::create([
            'name' => 'Chef Ejemplo',
            'email' => 'chef@gmail.com',
            'password' => bcrypt('123456')
        ]);
        $chefRole = Role::create(['name' => 'Chef']);
        $chefRole->syncPermissions([
            'recipe-list', 'recipe-create', 'recipe-edit',
            'tag-list',
            'ingredient-list',
        ]);
        $chef->assignRole($chefRole);

        // --- Rol Visitante: solo ver recetas ---
        $visitante = User::create([
            'name' => 'Visitante Ejemplo',
            'email' => 'visitante@gmail.com',
            'password' => bcrypt('123456')
        ]);
        $visitanteRole = Role::create(['name' => 'Visitante']);
        $visitanteRole->syncPermissions([
            'recipe-list',
            'tag-list',
            'ingredient-list',
        ]);
        $visitante->assignRole($visitanteRole);
    }
}