<?php

use App\Materia;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Models\Role;

class MateriaTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('materias')->delete();

        Materia::create([
            'id' => '6302',
            'nombre' => 'Sistemas de Informacion',
            'creditos' => '5',
            'semestre' => '5'
        ]);

        Materia::create([
            'id' => '6003',
            'nombre' => 'Comunicacion de Datos',
            'creditos' => '6',
            'semestre' => '5'
        ]);

        Materia::create([
            'id' => '6303',
            'nombre' => 'Bases de Datos',
            'creditos' => '5',
            'semestre' => '4'
        ]);

        Materia::create([
            'id' => '6211',
            'nombre' => 'Interaccion Humano-Computador',
            'creditos' => '5',
            'semestre' => '6'
        ]);

        Materia::create([
            'id' => '6109',
            'nombre' => 'Calculo Cientifico',
            'creditos' => '6',
            'semestre' => '5'
        ]);

    }

}
