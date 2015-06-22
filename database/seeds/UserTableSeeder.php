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

        $role_admin_system = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrador del Sistema' // optional
        ]);

        $role_director = Role::create([
            'name' => 'Director',
            'slug' => 'director',
            'description' => 'Director de Escuela' // optional
        ]);

        $role_admin_escuela = Role::create([
            'name' => 'Administrador de Escuela',
            'slug' => 'administrator',
            'description' => 'Administrador de Escuela' // optional
        ]);

        $role_jefe = Role::create([
            'name' => 'Jefe',
            'slug' => 'departmenthead',
            'description' => 'Jefe de Departamento' // optional
        ]);

        $role_cc = Role::create([
            'name' => 'Coordinador de Centro',
            'slug' => 'centercoordinator',
            'description' => 'Coordinador de Centro' // optional
        ]);

        $role_cm = Role::create([
            'name' => 'Coordinador de Materia',
            'slug' => 'coursecoordinator',
            'description' => 'Coordinador de Materia' // optional
        ]);

        $role_profesor = Role::create([
            'name' => 'Profesor',
            'slug' => 'professor',
            'description' => 'Profesor' // optional
        ]);

        $role_preparador = Role::create([
            'name' => 'Prepa',
            'slug' => 'teacherhelper',
            'description' => 'Preparador' // optional
        ]);

        $role_auxiliar = Role::create([
            'name' => 'Auxiliar',
            'slug' => 'teacherassistant',
            'description' => 'Auxiliar Docente' // optional
        ]);

        $role_secretaria_dpto = Role::create([
            'name' => 'Secretaria de Dpto',
            'slug' => 'departmentsecretary',
            'description' => 'Secretaria de Departamento' // optional
        ]);

        $role_secretaria_dir = Role::create([
            'name' => 'Secretaria de Dir',
            'slug' => 'directionsecretary',
            'description' => 'Secretaria de Direccion' // optional
        ]);

        User::create([
            'email' => 'sudo@ciens.ucv.ve',
            'password' => Hash::make('sudopwd'),
            'name' => 'Administrador',
            'lastname' => 'del Sistema'
        ]) -> attachRole($role_admin_system);

        User::create([
            'email' => 'dir@ciens.ucv.ve',
            'password' => Hash::make('director'),
            'name' => 'Director',
            'lastname' => 'de Escuela'
        ]) -> attachRole($role_director);

        User::create([
            'email' => 'jefe@ciens.ucv.ve',
            'password' => Hash::make('jefepwd'),
            'name' => 'Jefe',
            'lastname' => 'de Departamento'
        ]) -> attachRole($role_jefe);

        User::create([
            'email' => 'escuela@ciens.ucv.ve',
            'password' => Hash::make('escuela'),
            'name' => 'Administrador',
            'lastname' => 'de Escuela'
        ]) -> attachRole($role_admin_escuela);

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

        $user = User::create([
            'email' => 'prof1m221@ciens.ucv.ve',
            'password' => Hash::make('prof1m221'),
            'name' => 'Profesor',
            'lastname' => 'Acosta'
        ]);

        $user->attachRole($role_profesor);
        $user->attachRole($role_cm);

        User::create([
            'email' => 'prof2m222@ciens.ucv.ve',
            'password' => Hash::make('prof2m222'),
            'name' => 'Profesora',
            'lastname' => 'Yusneyi'
        ]) -> attachRole($role_profesor);

        User::create([
            'email' => 'prepa1m111@ciens.ucv.ve',
            'password' => Hash::make('prepa1m111'),
            'name' => 'Prepa',
            'lastname' => '1'
        ]) -> attachRole($role_preparador);

        User::create([
            'email' => 'prepa2m111@ciens.ucv.ve',
            'password' => Hash::make('prepa2m111'),
            'name' => 'Prepa',
            'lastname' => '2'
        ]) -> attachRole($role_preparador);

        User::create([
            'email' => 'prepa1m221@ciens.ucv.ve',
            'password' => Hash::make('prepa1m221'),
            'name' => 'Prepa',
            'lastname' => '1'
        ]) -> attachRole($role_preparador);

        User::create([
            'email' => 'aux1@ciens.ucv.ve',
            'password' => Hash::make('aux1'),
            'name' => 'Auxiliar',
            'lastname' => 'Docente'
        ]) -> attachRole($role_auxiliar);

        User::create([
            'email' => 'aux2@ciens.ucv.ve',
            'password' => Hash::make('aux2'),
            'name' => 'Auxiliar',
            'lastname' => 'Docente'
        ]) -> attachRole($role_auxiliar);

        User::create([
            'email' => 'sdep@ciens.ucv.ve',
            'password' => Hash::make('sdeppwd'),
            'name' => 'Secretaria',
            'lastname' => 'de Departamento'
        ]) -> attachRole($role_secretaria_dpto);

        User::create([
            'email' => 'sdir1@ciens.ucv.ve',
            'password' => Hash::make('sdir1pwd'),
            'name' => 'Secretaria',
            'lastname' => 'de Direccion'
        ]) -> attachRole($role_secretaria_dir);

        User::create([
            'email' => 'sdir2@ciens.ucv.ve',
            'password' => Hash::make('sdir2pwd'),
            'name' => 'Secretaria',
            'lastname' => 'de Direccion'
        ]) -> attachRole($role_secretaria_dir);

    }

}
