<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LatestMatches extends Model
{
    protected $fillable = [
        'idEvent', 'strEvent', 'strLeague', 'idLeague', 'intRound',
        'strSeason', 'strHomeTeam', 'strAwayTeam', 'intHomeScore',
        'intAwayScore', 'dateEvent', 'strTime', 'category'
    ];
}
