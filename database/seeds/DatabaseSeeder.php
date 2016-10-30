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
        $this->call(DefaultAdminSeed::class);
        $this->call(DefaultPermitsSeed::class);
        $this->call(DefaultDutiesSeed::class);
    }
}
