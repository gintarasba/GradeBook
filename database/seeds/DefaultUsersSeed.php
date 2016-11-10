<?php

use Illuminate\Database\Seeder;

class DefaultUsersSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // KT 4-1
        $group = App\Group::where('id', 1)->first();

        // Mokinys
        $duty = App\Duty::where('id', 1)->first();

        $user = new App\User();
        $user->loginName = "Gintaras.Ba4465";
        $user->name      = "Gintaras";
        $user->second_name = "Baltrėnas";
        $user->pcode     = "39506030598";
        $user->password  = \Hash::make('qwerty');
        $user->level     = 1;
        $user->save();
        $user->group()->attach($group);
        $user->duties()->attach($duty);

        $user2 = new App\User();
        $user2->loginName = "Denis.St4562";
        $user2->name      = "Denis";
        $user2->second_name = "Stagniūnas";
        $user2->pcode     = "39506030597";
        $user2->password  = \Hash::make('qwerty');
        $user2->level     = 1;
        $user2->save();
        $user2->group()->attach($group);
        $user2->duties()->attach($duty);
    }
}
