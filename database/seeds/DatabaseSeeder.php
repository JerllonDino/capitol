<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        
        for ($x=0; $x<500000; $x++) {
            DB::table('dnlx_group')->insert([
                'name' => str_random(4).str_random(4),
                'description' => str_random(3),
            ]);
        }
    }
}
