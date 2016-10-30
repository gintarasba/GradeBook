<?php

use Illuminate\Database\Seeder;

class DefaultPermitsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permit = new App\Permit();
        $permit->title = "Peržiūrėti savo pažymius";
        $permit->code  = "USER_MARKS_VIEW";
        $permit->comment = "Davus šį leidima vartotojui, jis galės peržiūrėti savo pažymių knygutę.";
        $permit->save();

        $permit = new App\Permit();
        $permit->title = "Peržiūrėti vartotojo pažymius";
        $permit->code  = "OBJECT_MARKS_VIEW";
        $permit->comment = "Davus šį leidima vartotojui, jis galės peržiūrėti kitų vartotojų pažymius.";
        $permit->save();

        $permit = new App\Permit();
        $permit->title = "Redaguoti vartotojo pažymius";
        $permit->code  = "OBJECT_MARKS_EDIT";
        $permit->comment = "Davus šį leidima vartotojui, jis galės redaguoti kitų vartotojų pažymius.";
        $permit->save();

        $permit = new App\Permit();
        $permit->title = "Až visiems";
        $permit->code  = "PM_ALL";
        $permit->comment = "Davus šį leidima vartotojui, jis galės siųsti asmenine žinute visiems.";
        $permit->save();
    }
}
