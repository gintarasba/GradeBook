<?php

use Illuminate\Database\Seeder;

class DefaultGroupsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dalykai/pamokos
        $math = App\Subject::where('id', 1)->first();
        $ltu = App\Subject::where('id', 2)->first();
        $eng = App\Subject::where('id', 3)->first();


        $group = new App\Group();
        $group->title = "KT 4-1";
        $group->save();
        //$group->subjects()->attach($math);
        //$group->subjects()->attach($ltu);
        //$group->subjects()->attach($eng);

        $group2 = new App\Group();
        $group2->title = "KT 4-2";
        $group2->save();
        //$group2->subjects()->attach($math);
        //$group2->subjects()->attach($ltu);
        //$group2->subjects()->attach($eng);


        $groupsList = [
            'KT', 'AV','IT', 'PRO','Fancy', 'Den'
        ];

        $groupsListLength = count($groupsList);

        $LIMIT = 1000;
        for ($i = 0; $i < $LIMIT; $i ++) {
            $group2 = new App\Group();
            $randTitle = $groupsList[mt_rand(0, $groupsListLength-1)].' '.$i.''.mt_rand($i, $i+2);
            $group2->title = $randTitle;
            $group2->save();
            //$group2->subjects()->attach($math);
            //$group2->subjects()->attach($ltu);
            //$group2->subjects()->attach($eng);
        }
    }
}
