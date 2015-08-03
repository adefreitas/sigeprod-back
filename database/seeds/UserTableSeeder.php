<?php

use \Hash;
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
            'name' => 'Zenaida',
            'lastname' => 'Castillo'
        ]) -> attachRole($role_director);


        /**********************************************************
        ******************* Jefe de Departamento ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'jefe@ciens.ucv.ve',
            'password' => Hash::make('jefe'),
            'name' => 'Robinson',
            'lastname' => 'Rivas'
        ]);

        $user->attachRole($role_jefe);

        $professor =   Professor::create([
            'dedication' => 'Completa',
            'center_id' => '11',
            'status' => 'Activo',
            'proposition_sent' => false
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
          'name' => 'Eric',
          'lastname' => 'Gamess'
        ]);

        $user->attachRole($role_cc);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '11',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

                /**********************************************************
        ***************** Coordinador de Centro 22 ****************
        ***********************************************************/

        $user = User::create([
          'email' => 'cc22@ciens.ucv.ve',
          'password' => Hash::make('cc22'),
          'name' => 'Rhadamés',
          'lastname' => 'Carmona'
        ]);

        $user->attachRole($role_cc);
        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '22',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();


        /**********************************************************
        ***************** Coordinador de Centro 33 ****************
        ***********************************************************/

        $user = User::create([
            'email' => 'cc33@ciens.ucv.ve',
            'password' => Hash::make('cc33'),
            'name' => 'Concettina',
            'lastname' => 'Di Vasta'
        ]);

        $user->attachRole($role_cc);


        $professor = Professor::create([
            'dedication' => 'Medio Tiempo',
            'center_id' => '33',
            'status' => 'Activo',
            'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ***************** Coordinador de Centro 44 ****************
        ***********************************************************/

        $user = User::create([
          'email' => 'cc44@ciens.ucv.ve',
          'password' => Hash::make('cc44'),
          'name' => 'Carlos',
          'lastname' => 'Acosta'
        ]);

        $user->attachRole($role_cc);
        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '44',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ***************** Coordinador de Centro 55 ****************
        ***********************************************************/

        $user = User::create([
          'email' => 'cc55@ciens.ucv.ve',
          'password' => Hash::make('cc55'),
          'name' => 'Zenaida',
          'lastname' => 'Castillo'
        ]);

        $user->attachRole($role_cc);
        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '55',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ***************** Coordinador de Centro 66 ****************
        ***********************************************************/

        $user = User::create([
          'email' => 'cc66@ciens.ucv.ve',
          'password' => Hash::make('cc66'),
          'name' => 'Eleonora',
          'lastname' => 'Acosta'
        ]);

        $user->attachRole($role_cc);
        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '66',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ***************** Coordinador de Centro 77 ****************
        ***********************************************************/

        $user = User::create([
          'email' => 'cc77@ciens.ucv.ve',
          'password' => Hash::make('cc77'),
          'name' => 'Adrian',
          'lastname' => 'Bottini'
        ]);

        $user->attachRole($role_cc);
        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '77',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ***************** Coordinador de Centro 88 ****************
        ***********************************************************/

        $user = User::create([
          'email' => 'cc88@ciens.ucv.ve',
          'password' => Hash::make('cc88'),
          'name' => 'Yusneyi',
          'lastname' => 'Carballo'
        ]);

        $user->attachRole($role_cc);
        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '88',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        **************** Coordinador de Materia 111 ***************
        ***********************************************************/

        $user = User::create([
            'email' => 'cm111@ciens.ucv.ve',
            'password' => Hash::make('cm111'),
            'name' => 'Gustavo',
            'lastname' => 'Torres'
        ]);

        $user->attachRole($role_cm);

        $user->attachRole($role_cc);

        $professor = Professor::create([
          'dedication' => 'Medio Tiempo',
          'center_id' => '33',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        **************** Coordinador de Materia 112 ***************
        ***********************************************************/

        $user = User::create([
            'email' => 'cm112@ciens.ucv.ve',
            'password' => Hash::make('cm112'),
            'name' => 'Libia',
            'lastname' => 'Bernal'
        ]);

        $user -> attachRole($role_cm);

        $professor =  Professor::create([
          'dedication' => 'Contratado',
          'center_id' => '11',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ******************* Profesor 1 Centro 44 ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'prof1c44@ciens.ucv.ve',
            'password' => Hash::make('prof1c44'),
            'name' => 'Victor',
            'lastname' => 'Felipe'
        ]);

        $user->attachRole($role_profesor);


        $professor = Professor::create([
          'dedication' => 'Contratado',
          'center_id' => '44',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ******************* Profesor Materia 222 ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'prof2m222@ciens.ucv.ve',
            'password' => Hash::make('prof2m222'),
            'name' => 'Jaime',
            'lastname' => 'Blanco'
        ]);

        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '66',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

                /**********************************************************
        ******************* Profesor 2 Centro 44 ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'prof2c44@ciens.ucv.ve',
            'password' => Hash::make('prof2c44'),
            'name' => 'Jaime',
            'lastname' => 'Parada'
        ]);

        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '44',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ******************* Profesor 1 Centro 22 ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'prof1c22@ciens.ucv.ve',
            'password' => Hash::make('prof1c22'),
            'name' => 'Hector',
            'lastname' => 'Navarro'
        ]);

        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '22',
          'status' => 'Activo',
          'proposition_sent' => false
        ]);

        $professor->user()->associate($user);

        $professor->save();

        /**********************************************************
        ******************* Profesor 1 Centro 11 ******************
        ***********************************************************/

        $user = User::create([
            'email' => 'prof1c11@ciens.ucv.ve',
            'password' => Hash::make('prof1c11'),
            'name' => 'Aparicio',
            'lastname' => 'Peña'
        ]);

        $user -> attachRole($role_profesor);

        $professor = Professor::create([
          'dedication' => 'Completa',
          'center_id' => '11',
          'status' => 'Activo',
          'proposition_sent' => false
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
            'name' => 'Christian',
            'lastname' => 'Brites'
        ]) -> attachRole($role_auxiliar);

        User::create([
            'email' => 'aux2@ciens.ucv.ve',
            'password' => Hash::make('aux2'),
            'name' => 'Andrés',
            'lastname' => 'De Freitas'
        ]) -> attachRole($role_auxiliar);


        /**********************************************************
        **************** Secretaria de Departamento ***************
        ***********************************************************/

        User::create([
            'email' => 'sdep@ciens.ucv.ve',
            'password' => Hash::make('sdep'),
            'name' => 'Gleydis',
            'lastname' => 'Caraballo'
        ]) -> attachRole($role_secretaria_dpto);

        /**********************************************************
        ***************** Secretarias de Direccion ****************
        ***********************************************************/

        User::create([
            'email' => 'sdir1@ciens.ucv.ve',
            'password' => Hash::make('sdir1'),
            'name' => 'Marisela',
            'lastname' => 'Algo'
        ]) -> attachRole($role_secretaria_dir);

        User::create([
            'email' => 'sdir2@ciens.ucv.ve',
            'password' => Hash::make('sdir2'),
            'name' => 'Secretaria',
            'lastname' => 'de Direccion'
        ]) -> attachRole($role_secretaria_dir);

    }

}
