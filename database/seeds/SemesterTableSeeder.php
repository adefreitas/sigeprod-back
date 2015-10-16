<?php

use App\Semester;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Models\Role;

class SemesterTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('semesters')->delete();

        Semester::create([
            'name' => 'Semestre I-2015',
            'begins_at' => Carbon::createFromFormat('d/m/Y', '05/01/2015'),
            'ends_at' => Carbon::createFromFormat('d/m/Y', '20/05/2015'),
            'intensive' => false
        ]);

        Semester::create([
            'name' => 'Semestre intensivo 2015',
            'begins_at' => Carbon::createFromFormat('d/m/Y', '30/05/2015'),
            'ends_at' => Carbon::createFromFormat('d/m/Y', '10/07/2015'),
            'intensive' => true
        ]);

        Semester::create([
            'name' => 'Semestre II-2015',
            'begins_at' => Carbon::createFromFormat('d/m/Y', '20/07/2015'),
            'ends_at' => Carbon::createFromFormat('d/m/Y', '19/12/2015'),
            'intensive' => false
        ]);

    }

}
