<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLatestMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('latest_matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idEvent');
            $table->string('strEvent');
            $table->string('strLeague');
            $table->string('idLeague');
            $table->string('strSeason');
            $table->string('strHomeTeam');
            $table->string('strAwayTeam');
            $table->string('intHomeScore')->nullable();
            $table->string('intAwayScore')->nullable();
            $table->date('dateEvent');
            $table->string('strTime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('latest_matches');
    }
}
