<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
  
class PermissionTableSeeder extends Seeder
{
     use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',
           'recipe-list',
           'recipe-create',
           'recipe-edit',
           'recipe-delete',
           'tag-list',
           'tag-create',
           'tag-edit',
           'tag-delete',
           'ingredient-list',
           'ingredient-create',
           'ingredient-edit',
           'ingredient-delete',
        ];
        
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}