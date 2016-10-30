<?php

use Illuminate\Database\Seeder;

class DefaultDutiesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $duty = new App\Duty();
        $duty->title = "Mokinys";
        $duty->save();
        $permit = App\Permit::where('code', 'USER_MARKS_VIEW')->first();
        $duty->permits()->attach($permit);

        $duty = new App\Duty();
        $duty->title = "Mokytojas";
        $duty->save();
        $permit = App\Permit::where('code', 'OBJECT_MARKS_VIEW')->first();
        $duty->permits()->attach($permit);
        $permit = App\Permit::where('code', 'OBJECT_MARKS_EDIT')->first();
        $duty->permits()->attach($permit);


    }
}
