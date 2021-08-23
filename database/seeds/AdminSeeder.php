<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(! User::whereId(1)->first())
            User::insert(
                array(
                    'id'        => 1,
                    'name'      => 'Administrador',
                    'email'     => 'ctbeca@admin.com',
                    'password'  => bcrypt("Ee81887127*"),
                    'created_at'=> date('Y-m-d H:m:s'),
                    'updated_at'=> date('Y-m-d H:m:s'),
                )
            );
    }
}
