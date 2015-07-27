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

        Center::find(33)
          ->courses()->save(
            Course::create([
                'id' => '6302',
                'name' => 'Sistemas de Información',
                'credits' => '5',
                'semester' => '5'
            ])
        );

        Center::find(11)
          ->courses()->save(
            Course::create([
                'id' => '6003',
                'name' => 'Comunicación de Datos',
                'credits' => '6',
                'semester' => '5'
            ])
        );

        Center::find(33)
          ->courses()->save(
            Course::create([
                'id' => '6303',
                'name' => 'Bases de Datos',
                'credits' => '5',
                'semester' => '4'
            ])
        );

        Center::find(66)
          ->courses()->save(
            Course::create([
                'id' => '6211',
                'name' => 'Interacción Humano-Computador',
                'credits' => '5',
                'semester' => '6'
            ])
        );

        Center::find(77)
          ->courses()->save(
            Course::create([
                'id' => '6109',
                'name' => 'Cálculo Científico',
                'credits' => '6',
                'semester' => '5'
            ])
        );

        Course::create([
                'id' => '6106',
                'name' => 'Matemáticas Discretas I',
                'credits' => '4',
                'semester' => '1'
        ]);

        Course::create([
                'id' => '6201',
                'name' => 'Algoritmos y Programación',
                'credits' => '6',
                'semester' => '1'
        ]);

        Course::create([
                'id' => '6301',
                'name' => 'Introducción a la Informática',
                'credits' => '4',
                'semester' => '1'
        ]);

        Course::create([
                'id' => '6202',
                'name' => 'Algoritmos y Estructuras de Datos',
                'credits' => '5',
                'semester' => '2'
        ]);



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
