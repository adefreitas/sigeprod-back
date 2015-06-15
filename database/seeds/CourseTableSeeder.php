<?php

use App\Course;
use App\Center;
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

        //Busco el centro al que pertenece la materia
        $centro = Center::find(33);

        $materia = Course::create([
            'id' => '6302',
            'name' => 'Sistemas de Informacion',
            'credits' => '5',
            'semester' => '5'
        ]);

        //Agrego la materia a la lista de materias del centro
//        $centro->materias()->save($materia);


        Course::create([
            'id' => '6003',
            'name' => 'Comunicacion de Datos',
            'credits' => '6',
            'semester' => '5'
        ]);

        Course::create([
            'id' => '6303',
            'name' => 'Bases de Datos',
            'credits' => '5',
            'semester' => '4'
        ]);

        Course::create([
            'id' => '6211',
            'name' => 'Interaccion Humano-Computador',
            'credits' => '5',
            'semester' => '6'
        ]);

        Course::create([
            'id' => '6109',
            'name' => 'Calculo Cientifico',
            'credits' => '6',
            'semester' => '5'
        ]);

    }

}
