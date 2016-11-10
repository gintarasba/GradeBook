<?php

use Illuminate\Database\Seeder;

class DefaultSubjectsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subject = new App\Subject();
        $subject->title = "Matematika";
        $subject->save();

        $subject2 = new App\Subject();
        $subject2->title = "Lietuvių kalba";
        $subject2->save();

        $subject3 = new App\Subject();
        $subject3->title = "Anglų kalba";
        $subject3->save();
    }
}
