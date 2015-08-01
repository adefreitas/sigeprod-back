<?php

use App\User;
use App\Center;
use App\Course;
use App\Professor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Models\Role;

class CourseTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('courses')->delete();

        /**********************************************************
        ************************* Materias ************************
        ***********************************************************/

        Center::find(66)
          ->courses()->save(
            Course::create([
                'id' => '6106',
                'name' => 'Matemáticas Discretas I',
                'type' => 'Obligatoria',
                'credits' => '4',
                'semester' => '1',
                'active' => true
            ])
        );

        Center::find(66)
          ->courses()->save(
            Course::create([
                'id' => '6201',
                'name' => 'Algoritmos y Programación',
                'type' => 'Obligatoria',
                'credits' => '6',
                'semester' => '1',
                'active' => true
            ])
        );

        Center::find(11)
          ->courses()->save(
            Course::create([
                'id' => '6301',
                'name' => 'Introducción a la Informática',
                'type' => 'Obligatoria',
                'credits' => '4',
                'semester' => '1',
                'active' => true
            ])
        );

        Center::find(22)
          ->courses()->save(
            Course::create([
                'id' => '6202',
                'name' => 'Algoritmos y Estructuras de Datos',
                'type' => 'Obligatoria',
                'credits' => '5',
                'semester' => '2',
                'active' => true
            ])
        );

        Center::find(44)
          ->courses()->save(
            Course::create([
                'id' => '6001',
                'name' => 'Organización y Estructura del Computador I',
                'type' => 'Obligatoria',
                'credits' => '6',
                'semester' => '2',
                'active' => true
            ])
        );

        Center::find(66)
          ->courses()->save(
            Course::create([
                'id' => '6107',
                'name' => 'Matemáticas Discretas II',
                'type' => 'Obligatoria',
                'credits' => '4',
                'semester' => '2',
                'active' => true
            ])
        );

        Center::find(44)
          ->courses()->save(
            Course::create([
                'id' => '6002',
                'name' => 'Organización y Estructura del Computador II',
                'type' => 'Obligatoria',
                'credits' => '5',
                'semester' => '3',
                'active' => true
            ])
        );

        Center::find(44)
          ->courses()->save(
            Course::create([
                'id' => '6203',
                'name' => 'Ingeniería del Software',
                'type' => 'Obligatoria',
                'credits' => '5',
                'semester' => '3',
                'active' => true
            ])
        );

        Center::find(77)
          ->courses()->save(
            Course::create([
                'id' => '6108',
                'name' => 'Matemáticas Discretas III',
                'type' => 'Obligatoria',
                'credits' => '4',
                'semester' => '4',
                'active' => true
            ])
        );

        Center::find(44)
          ->courses()->save(
            Course::create([
                'id' => '6004',
                'name' => 'Sistemas Operativos',
                'type' => 'Obligatoria',
                'credits' => '6',
                'semester' => '4',
                'active' => true
            ])
        );

        Center::find(33)
          ->courses()->save(
            Course::create([
                'id' => '6303',
                'name' => 'Bases de Datos',
                'credits' => '5',
                'type' => 'Obligatoria',
                'semester' => '4',
                'active' => true
            ])
        );

        Center::find(77)
          ->courses()->save(
            Course::create([
                'id' => '6104',
                'name' => 'Probabilidad y Estadística',
                'type' => 'Obligatoria',
                'credits' => '5',
                'semester' => '4',
                'active' => true
            ])
        );

        Center::find(11)
          ->courses()->save(
            Course::create([
                'id' => '6003',
                'name' => 'Comunicación de Datos',
                'credits' => '6',
                'type' => 'Obligatoria',
                'semester' => '5',
                'active' => true
            ])
        );

        Center::find(33)
          ->courses()->save(
            Course::create([
                'id' => '6302',
                'name' => 'Sistemas de Información',
                'credits' => '5',
                'type' => 'Obligatoria',
                'semester' => '5',
                'active' => true
            ])
        );

        Center::find(55)
          ->courses()->save(
            Course::create([
                'id' => '6109',
                'name' => 'Cálculo Científico',
                'type' => 'Obligatoria',
                'credits' => '6',
                'semester' => '5',
                'active' => true
            ])
        );

        Center::find(55)
          ->courses()->save(
            Course::create([
                'id' => '6204',
                'name' => 'Lenguajes de Programación',
                'type' => 'Obligatoria',
                'credits' => '5',
                'semester' => '5',
                'active' => true
            ])
        );

        Center::find(66)
          ->courses()->save(
            Course::create([
                'id' => '6211',
                'name' => 'Interacción Humano-Computador',
                'credits' => '5',
                'type' => 'Electiva-Optativa',
                'semester' => '6',
                'active' => true
            ])
        );

        Center::find(66)
          ->courses()->save(
            Course::create([
                'id' => '6221',
                'name' => 'Aplicaciones con la Tecnología Internet',
                'credits' => '5',
                'type' => 'Electiva-Optativa',
                'semester' => '6',
                'active' => true
            ])
        );

        Center::find(11)
          ->courses()->save(
            Course::create([
                'id' => '6012',
                'name' => 'Redes de Computadoras',
                'credits' => '6',
                'type' => 'Electiva-Optativa',
                'semester' => '6',
                'active' => true
            ])
        );

        Center::find(33)
          ->courses()->save(
            Course::create([
                'id' => '6311',
                'name' => 'Administración de Bases de Datos',
                'credits' => '5',
                'type' => 'Electiva-Optativa',
                'semester' => '6',
                'active' => true
            ])
        );

        Center::find(66)
          ->courses()->save(
            Course::create([
                'id' => '6023',
                'name' => 'Comercio Electrónico',
                'credits' => '5',
                'type' => 'Electiva',
                'semester' => '7',
                'active' => true
            ])
        );

        Center::find(66)
          ->courses()->save(
            Course::create([
                'id' => '6332',
                'name' => 'Innovación Tecnológica',
                'credits' => '5',
                'type' => 'Electiva',
                'semester' => '8',
                'active' => true
            ])
        );

        Center::find(44)
          ->courses()->save(
            Course::create([
                'id' => '6241',
                'name' => 'Desarrollo de Aplicaciones Distribuidas',
                'credits' => '5',
                'type' => 'Electiva',
                'semester' => '7',
                'active' => true
            ])
        );


        /**********************************************************
        ***************** Coordinadores de Centro *****************
        ***********************************************************/

        Center::find(11)
          ->centerCoordinator()->attach(
            User::where('email', '=', 'cc11@ciens.ucv.ve')->firstOrFail()->professor
        );

        Center::find(11)
          ->centerCoordinator()->attach(
            User::where('email', '=', 'cm111@ciens.ucv.ve')->firstOrFail()->professor
        );

        Center::find(22)
          ->centerCoordinator()->attach(
            User::where('email', '=', 'cc22@ciens.ucv.ve')->firstOrFail()->professor
        );

        Center::find(33)
          ->centerCoordinator()->attach(
            User::where('email', '=', 'cc33@ciens.ucv.ve')->firstOrFail()->professor
        );

        Center::find(44)
          ->centerCoordinator()->attach(
            User::where('email', '=', 'cc44@ciens.ucv.ve')->firstOrFail()->professor
        );

        Center::find(55)
          ->centerCoordinator()->attach(
            User::where('email', '=', 'cc55@ciens.ucv.ve')->firstOrFail()->professor
        );

        Center::find(66)
          ->centerCoordinator()->attach(
            User::where('email', '=', 'cc66@ciens.ucv.ve')->firstOrFail()->professor
        );

        Center::find(77)
          ->centerCoordinator()->attach(
            User::where('email', '=', 'cc77@ciens.ucv.ve')->firstOrFail()->professor
        );

        Center::find(88)
          ->centerCoordinator()->attach(
            User::where('email', '=', 'cc88@ciens.ucv.ve')->firstOrFail()->professor
        );

        /**********************************************************
        **************** Coordinadores de Materia *****************
        ***********************************************************/

        $course = Course::find(6303);

        $course->courseCoordinator()->attach(
            User::where('email', '=', 'cm111@ciens.ucv.ve')->firstOrFail()->professor
        );

        $course->save();

        $course = Course::find(6003);

        $course->courseCoordinator()->attach(
          User::where('email', '=', 'cm112@ciens.ucv.ve')->firstOrFail()->professor
        );

        $course->save();

        /**********************************************************
        ************************ Profesores ***********************
        ***********************************************************/

    }

}
