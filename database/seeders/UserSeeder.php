<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'INT1',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT1')
        ]);

        DB::table('users')->insert([
            'username' => 'INT2',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT2')
        ]);

        DB::table('users')->insert([
            'username' => 'INT3',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT3')
        ]);

        DB::table('users')->insert([
            'username' => 'INT4',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT4')
        ]);

        DB::table('users')->insert([
            'username' => 'INT5',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT5')
        ]);

        DB::table('users')->insert([
            'username' => 'INT6',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT6')
        ]);

        DB::table('users')->insert([
            'username' => 'INT7',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT7')
        ]);

        DB::table('users')->insert([
            'username' => 'INT8',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT8')
        ]);

        DB::table('users')->insert([
            'username' => 'INT9',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT9')
        ]);

        DB::table('users')->insert([
            'username' => 'INT10',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT10')
        ]);

        DB::table('users')->insert([
            'username' => 'INT11',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT11')
        ]);

        DB::table('users')->insert([
            'username' => 'INT12',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT12')
        ]);

        DB::table('users')->insert([
            'username' => 'INT13',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT13')
        ]);

        DB::table('users')->insert([
            'username' => 'INT14',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT14')
        ]);

        DB::table('users')->insert([
            'username' => 'INT15',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT15')
        ]);

        DB::table('users')->insert([
            'username' => 'INT16',
            'number_of_surveys' => 0,
            'password' => Hash::make('FLINT16')
        ]);
    }
}
