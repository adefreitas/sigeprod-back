<?php

use App\TeacherHelper;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Models\Role;

class TeacherHelperTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('teacher_helpers')->delete();
        $id = 1000;

        for($i = 0; $i < 20; $i++){
            TeacherHelper::create([
                "id" => $id,
                "available" => 1,
                "type" => 1,
            ]);

            $id++;
        }
        for($i = 0; $i < 20; $i++){
            TeacherHelper::create([
                "id" => $id,
                "available" => 1,
                "type" => 2,
            ]);
            $id++;
        }
        for($i = 0; $i < 10; $i++){
            TeacherHelper::create([
                "id" => $id,
                "available" => 1,
                "type" => 3,
            ]);
            $id++;
        }

    }

}
