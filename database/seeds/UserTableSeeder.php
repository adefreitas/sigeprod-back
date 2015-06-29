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
            'email' => 'sudo@ciens.ucv.ve',
            'password' => Hash::make('sudopwd'),
            'name' => 'Administrador',
            'lastname' => 'del Sistema'
        ]) -> attachRole($role_admin_system);


        /**********************************************************
        ************************* Director ************************
        ***********************************************************/

        User::create([
            'email' => 'dir@ciens.ucv.ve',
            'password' => Hash::make('director'),
            'name' => 'Director',
            'lastname' => 'de Escuela'
        ]) -> attachRole($role_director);


        /**********************************************************
        ******************* Jefe de Departamento ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'jefe@ciens.ucv.ve',
            'password' => Hash::make('jefepwd'),
            'name' => 'Jefe',
            'lastname' => 'de Departamento'
        ]);

        $user -> professor() -> associate(
            Professor::create([
            'dedication' => 'Completa',
            'center_id' => '11',
            'status' => 'Activo'
            ])
        );

        $user -> attachRole($role_jefe);

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
            'password' => Hash::make('centro11'),
            'name' => 'Coordinador',
            'lastname' => 'de Centro'
        ]);

        $user -> professor() -> associate(
            Professor::create([
            'dedication' => 'Completa',
            'center_id' => '22',
            'status' => 'Activo'
            ])
        );

        $user -> attachRole($role_cc);

        /**********************************************************
        ***************** Coordinador de Centro 11 ****************
        ***********************************************************/

        $user = User::create([
            'email' => 'cc22@ciens.ucv.ve',
            'password' => Hash::make('centro22'),
            'name' => 'Coordinador',
            'lastname' => 'de Centro'
        ]);

        $user -> professor() -> associate(
            Professor::create([
            'dedication' => 'Medio Tiempo',
            'center_id' => '33',
            'status' => 'Activo'
            ])
        );

        $user -> attachRole($role_cc);

        /**********************************************************
        **************** Coordinador de Materia 111 ***************
        ***********************************************************/

        $user = User::create([
            'email' => 'cm111@ciens.ucv.ve',
            'password' => Hash::make('centro111'),
            'name' => 'Coordinador',
            'lastname' => 'de Materia'
        ]);

        $user -> professor() -> associate(
            Professor::create([
            'dedication' => 'Medio Tiempo',
            'center_id' => '44',
            'status' => 'Activo'
            ])
        );

        $user -> attachRole($role_cm);

        /**********************************************************
        **************** Coordinador de Materia 112 ***************
        ***********************************************************/

        $user = User::create([
            'email' => 'cm112@ciens.ucv.ve',
            'password' => Hash::make('centro112'),
            'name' => 'Coordinador',
            'lastname' => 'de Materia'
        ]);

        $user -> professor() -> associate(
            Professor::create([
            'dedication' => 'Contratado',
            'center_id' => '55',
            'status' => 'Activo'
            ])
        );

        $user -> attachRole($role_cm);


        /**********************************************************
        ******************* Profesor Materia 221 ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'prof1m221@ciens.ucv.ve',
            'password' => Hash::make('prof1m221'),
            'name' => 'Profesor',
            'lastname' => 'Acosta'
        ]);

        $user -> professor() -> associate(
            Professor::create([
            'dedication' => 'Contratado',
            'center_id' => '66',
            'status' => 'Activo'
            ])
        );

        $user->attachRole($role_profesor);
        $user->attachRole($role_cm);

        /**********************************************************
        ******************* Profesor Materia 222 ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'prof2m222@ciens.ucv.ve',
            'password' => Hash::make('prof2m222'),
            'name' => 'Profesora',
            'lastname' => 'Yusneyi'
        ]);

        $user -> professor() -> associate(
            Professor::create([
            'dedication' => 'Completa',
            'center_id' => '77',
            'status' => 'Activo'
            ])
        );

        $user -> attachRole($role_profesor);

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
