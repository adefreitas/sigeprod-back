<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Models\Role;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $role_admin = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrador del Sistema' // optional
        ]);

        $role_jefe = Role::create([
            'name' => 'Jefe',
            'slug' => 'jefe',
            'description' => 'Jefe de Departamento' // optional
        ]);

        $role_cc = Role::create([
            'name' => 'Coordinador de Centro',
            'slug' => 'cc',
            'description' => 'Coordinador de Centro' // optional
        ]);

        $role_cm = Role::create([
            'name' => 'Coordinador de Materia',
            'slug' => 'cm',
            'description' => 'Coordinador de Materia' // optional
        ]);

        $role_profesor = Role::create([
            'name' => 'Profesor',
            'slug' => 'profesor',
            'description' => 'Profesor' // optional
        ]);

        User::create([
            'email' => 'sudo@ciens.ucv.ve',
            'password' => Hash::make('sudopwd'),
            'name' => 'Administrador',
            'lastname' => 'del Sistema'
        ]) -> attachRole($role_admin);

        User::create([
            'email' => 'jefe@ciens.ucv.ve',
            'password' => Hash::make('jefepwd'),
            'name' => 'Jefe',
            'lastname' => 'de Departamento'
        ]) -> attachRole($role_jefe);

        User::create([
            'email' => 'cc11@ciens.ucv.ve',
            'password' => Hash::make('centro11'),
            'name' => 'Coordinador',
            'lastname' => 'de Centro'
        ]) -> attachRole($role_cc);

        User::create([
            'email' => 'cc22@ciens.ucv.ve',
            'password' => Hash::make('centro22'),
            'name' => 'Coordinador',
            'lastname' => 'de Centro'
        ]) -> attachRole($role_cc);

        User::create([
            'email' => 'cm111@ciens.ucv.ve',
            'password' => Hash::make('centro111'),
            'name' => 'Coordinador',
            'lastname' => 'de Materia'
        ]) -> attachRole($role_cm);

        User::create([
            'email' => 'cm112@ciens.ucv.ve',
            'password' => Hash::make('centro112'),
            'name' => 'Coordinador',
            'lastname' => 'de Materia'
        ]) -> attachRole($role_cm);

    }

}
