<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DutiesPermits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duties_permits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('duty_id')->unsigned();
            $table->integer('permit_id')->unsigned();
            $table->timestamps();

            //$table->foreign('duty_id')->references('id')->on('users_duties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('duties_permits');
    }
}
