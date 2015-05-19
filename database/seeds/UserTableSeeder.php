<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'email' => 'user@email.com',
            'password' => Hash::make('password'),
            'name' => 'Usuario',
            'lastname' => 'De Prueba',
        ]);

    }

}
