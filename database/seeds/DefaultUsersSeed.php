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

        // subjects
        $mathSubject = App\Subject::where('id', 1)->first();
        $litLangSubject = App\Subject::where('id', 2)->first();
        $enLangSubject = App\Subject::where('id', 3)->first();




        // Mokytojas
        $teacher = App\Duty::where('id', 2)->first();

        // suseedinu kad nereiktu spaudinet per admin cp :D .. :D
        $user = App\User::where('id', 1)->first();
        $user->duties()->attach($teacher);
        $user->group()->attach($group);
        $user->subjects()->attach($mathSubject);

        $user2 = App\User::where('id', 2)->first();
        $user2->duties()->attach($teacher);
        $user2->group()->attach($group);
        $user2->subjects()->attach($mathSubject);

        // Mokinys
        $duty = App\Duty::where('id', 1)->first();



        $randUserNamesList = [
            'Gintaras', 'Denis', 'Petras',
            'Lukas', 'Antanas','Juozas',
            'Abramas', 'Abraomas', 'Achilas',
            'Adalbertas','Adamas', 'Alpis',
            'Alvidas', 'Balys', 'Baltazaras',
            'Baltramiejus', 'Baltrus',
            'Česius', 'Dainius', 'Dalius',
            'Edgaras', 'Edmundas', 'Eduardas',
            'Damijonas', 'Danas'
        ];
        $randUserNamesListLength = count($randUserNamesList);
        $randUsersSNamesList = [
            'Baltrėnas', 'Stagniūnas', 'Čiginskas',
            'Testauskas', 'Basanavičius', 'Kavaliauskas',
            'Bacevičius', 'Dalbokaitis', 'Danyla', 'Danielius',
            'Dapkutis', 'Gedvilas', 'Hamakas', 'Jablonskis',
            'Jagminas', 'Jankauskas', 'Janulis', 'Kanišauskas'

        ];
        $randUserSNamesListLength = count($randUsersSNamesList);

        $randGroupList = App\Group::all();
        $randGroupListLength = count($randGroupList);
        $usersLIMIT = 100;
        $perGroupLimit = 20;
        $usersInGroup = 0;
        $groupId = 1;
        for ($i = 1; $i < $usersLIMIT; $i ++) {
            //Faker\Factory::create()


            $randUser = new App\User();
            $randName = $randUserNamesList[mt_rand(0, $randUserNamesListLength-1)];
            $randSName = $randUsersSNamesList[mt_rand(0, $randUserSNamesListLength-1)];
            $randPcode = "3950".mt_rand(1111111, 9999999);

            $existingPcodeCheck = App\User::where('pcode', $randPcode)->first();
            if (!empty($existingPcodeCheck)) {
                continue;
            }

            if ($usersInGroup >= $perGroupLimit) {
                $groupId ++;
                $usersInGroup = 0;
            }
            $randLoginName = $randName.".".mb_substr($randSName, 0, 2)."".mt_rand(1111, 9999);
            $randUser->loginName = $randLoginName;
            $randUser->name      = $randName;
            $randUser->second_name = $randSName;
            $randUser->pcode     = $randPcode;
            $randUser->password  = \Hash::make('qwerty');
            $randUser->email     = $randLoginName.'@dienynas.itblur.lt';
            $randUser->level     = 1;
            $randUser->updated_at = 10;
            $randUser->save();

            $randUser->group()->attach($groupId);
            $usersInGroup ++;

            $randUser->duties()->attach($duty);
            $randUser->subjects()->attach($mathSubject);
            $randUser->subjects()->attach($litLangSubject);
            $randUser->subjects()->attach($enLangSubject);
        }
    }
}
