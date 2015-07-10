<?php

use App\User;
use App\Professor;
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

        /**********************************************************
        ************************** Roles **************************
        ***********************************************************/


        $role_admin_system = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrador del Sistema'
        ]);

        $role_director = Role::create([
            'name' => 'Director',
            'slug' => 'director',
            'description' => 'Director de Escuela'
        ]);

        $role_admin_escuela = Role::create([
            'name' => 'Administrador de Escuela',
            'slug' => 'administrator',
            'description' => 'Administrador de Escuela'
        ]);

        $role_jefe = Role::create([
            'name' => 'Jefe',
            'slug' => 'departmenthead',
            'description' => 'Jefe de Departamento'
        ]);

        $role_cc = Role::create([
            'name' => 'Coordinador de Centro',
            'slug' => 'centercoordinator',
            'description' => 'Coordinador de Centro'
        ]);

        $role_cm = Role::create([
            'name' => 'Coordinador de Materia',
            'slug' => 'coursecoordinator',
            'description' => 'Coordinador de Materia'
        ]);

        $role_profesor = Role::create([
            'name' => 'Profesor',
            'slug' => 'professor',
            'description' => 'Profesor'
        ]);

        $role_preparador = Role::create([
            'name' => 'Prepa',
            'slug' => 'teacherhelper',
            'description' => 'Preparador'
        ]);

        $role_auxiliar = Role::create([
            'name' => 'Auxiliar',
            'slug' => 'teacherassistant',
            'description' => 'Auxiliar Docente'
        ]);

        $role_secretaria_dpto = Role::create([
            'name' => 'Secretaria de Dpto',
            'slug' => 'departmentsecretary',
            'description' => 'Secretaria de Departamento'
        ]);

        $role_secretaria_dir = Role::create([
            'name' => 'Secretaria de Dir',
            'slug' => 'directionsecretary',
            'description' => 'Secretaria de Direccion'
        ]);


        /**********************************************************
        ********************** Super Usuario **********************
        ***********************************************************/


        User::create([
            'email' => 'su@ciens.ucv.ve',
            'password' => Hash::make('su'),
            'name' => 'Administrador',
            'lastname' => 'del Sistema'
        ]) -> attachRole($role_admin_system);


        /**********************************************************
        ************************* Director ************************
        ***********************************************************/

        User::create([
            'email' => 'dir@ciens.ucv.ve',
            'password' => Hash::make('dir'),
            'name' => 'Director',
            'lastname' => 'de Escuela'
        ]) -> attachRole($role_director);


        /**********************************************************
        ******************* Jefe de Departamento ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'jefe@ciens.ucv.ve',
            'password' => Hash::make('jefe'),
            'name' => 'Jefe',
            'lastname' => 'de Departamento'
        ]);

        $user->attachRole($role_jefe);

        $professor =   Professor::create([
            'dedication' => 'Completa',
            'center_id' => '11',
            'status' => 'Activo'
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        *************** Administrador de la Escuela ***************
        ***********************************************************/

        User::create([
            'email' => 'escuela@ciens.ucv.ve',
            'password' => Hash::make('escuela'),
            'name' => 'Administrador',
            'lastname' => 'de Escuela'
        ]) -> attachRole($role_admin_escuela);


        /**********************************************************
        ***************** Coordinador de Centro 11 ****************
        ***********************************************************/

        $user = User::create([
          'email' => 'cc11@ciens.ucv.ve',
          'password' => Hash::make('cc11'),
          'name' => 'Coordinador',
          'lastname' => 'de Centro'
        ]);

        $user->attachRole($role_cc);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '11',
          'status' => 'Activo'
        ]);

        $professor->user()->associate($user);

        $professor->save();


        /**********************************************************
        ***************** Coordinador de Centro 33 ****************
        ***********************************************************/

        $user = User::create([
            'email' => 'cc33@ciens.ucv.ve',
            'password' => Hash::make('cc33'),
            'name' => 'Coordinador',
            'lastname' => 'de Centro'
        ]);

        $user->attachRole($role_cc);


        $professor = Professor::create([
            'dedication' => 'Medio Tiempo',
            'center_id' => '33',
            'status' => 'Activo'
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        **************** Coordinador de Materia 111 ***************
        ***********************************************************/

        $user = User::create([
            'email' => 'cm111@ciens.ucv.ve',
            'password' => Hash::make('cm111'),
            'name' => 'Coordinador',
            'lastname' => 'de Materia'
        ]);

        $user->attachRole($role_cm);

        $professor = Professor::create([
          'dedication' => 'Medio Tiempo',
          'center_id' => '33',
          'status' => 'Activo'
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        **************** Coordinador de Materia 112 ***************
        ***********************************************************/

        $user = User::create([
            'email' => 'cm112@ciens.ucv.ve',
            'password' => Hash::make('cm112'),
            'name' => 'Coordinador',
            'lastname' => 'de Materia'
        ]);

        $user -> attachRole($role_cm);

        $professor =  Professor::create([
          'dedication' => 'Contratado',
          'center_id' => '11',
          'status' => 'Activo'
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ******************* Profesor Materia 221 ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'prof1m221@ciens.ucv.ve',
            'password' => Hash::make('prof1m221'),
            'name' => 'Profesor',
            'lastname' => 'Acosta'
        ]);

        $user->attachRole($role_profesor);


        $professor = Professor::create([
          'dedication' => 'Contratado',
          'center_id' => '66',
          'status' => 'Activo'
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ******************* Profesor Materia 222 ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'prof2m222@ciens.ucv.ve',
            'password' => Hash::make('prof2m222'),
            'name' => 'Profesora',
            'lastname' => 'Yusneyi'
        ]);

        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '77',
          'status' => 'Activo'
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        *********************** Preparadores **********************
        ***********************************************************/

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

        /**********************************************************
        ********************* Auxiliar Docente ********************
        ***********************************************************/

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


        /**********************************************************
        **************** Secretaria de Departamento ***************
        ***********************************************************/

        User::create([
            'email' => 'sdep@ciens.ucv.ve',
            'password' => Hash::make('sdeppwd'),
            'name' => 'Secretaria',
            'lastname' => 'de Departamento'
        ]) -> attachRole($role_secretaria_dpto);

        /**********************************************************
        ***************** Secretarias de Direccion ****************
        ***********************************************************/

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
