<?php

use App\Center;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Models\Role;

class CenterTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('centers')->delete();

        Center::create([
            'id' => '11',
            'name' => 'CICORE'
        ]);

        Center::create([
            'id' => '22',
            'name' => 'CCG'
        ]);

        Center::create([
            'id' => '33',
            'name' => 'CISI'
        ]);

        Center::create([
            'id' => '44',
            'name' => 'CCPD'
        ]);

        Center::create([
            'id' => '55',
            'name' => 'CCCT'
        ]);

        Center::create([
            'id' => '66',
            'name' => 'ISYS'
        ]);

        Center::create([
            'id' => '77',
            'name' => 'CIOMMA'
        ]);

    }

}
