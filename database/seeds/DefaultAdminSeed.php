<?php

use Illuminate\Database\Seeder;

class DefaultAdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new App\User();
        $user->loginName = "Admin";
        $user->name      = "Gintaras";
        $user->second_name = "Baltrėnas";
        $user->pcode     = "39506030599";
        $user->password  = \Hash::make('admin123');
        $user->level = 2; // 1 User 2 Administrator
        $user->save();
    }
}
