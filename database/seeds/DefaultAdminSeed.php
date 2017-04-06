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
        $user->loginName = "Gintaras.Ba4465";
        $user->uid       = '37.250.180.79';
        $user->name      = "Gintaras";
        $user->second_name = "Baltrėnas";
        $user->pcode     = "39506030599";
        $user->password  = \Hash::make('admin123');
        $user->email     = 'Gintaras210@gmail.com';
        $user->level = 2; // 1 User 2 Administrator
        $user->save();

        $user = new App\User();
        $user->loginName = "Denis.St4562";
        $user->uid       = '116.31.25.43';
        $user->name      = "Denis";
        $user->second_name = "Stagniūnas";
        $user->pcode     = "39506030600";
        $user->password  = \Hash::make('admin123');
        $user->email     = 'Denis.St@gmail.com';
        $user->level = 2; // 1 User 2 Administrator
        $user->updated_at = '1';
        $user->save();
    }
}
