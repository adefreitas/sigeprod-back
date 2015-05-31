<?php

use App\Centro;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Models\Role;

class CentroTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('centros')->delete();

        Centro::create([
            'id' => '11',
            'name' => 'CICORE'
        ]);

        Centro::create([
            'id' => '22',
            'name' => 'CCG'
        ]);

        Centro::create([
            'id' => '33',
            'name' => 'CISI'
        ]);

        Centro::create([
            'id' => '44',
            'name' => 'CCPD'
        ]);

        Centro::create([
            'id' => '55',
            'name' => 'CCCT'
        ]);

        Centro::create([
            'id' => '66',
            'name' => 'ISYS'
        ]);

        Centro::create([
            'id' => '77',
            'name' => 'CIOMMA'
        ]);

    }

}
