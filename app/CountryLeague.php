<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryLeague extends Model
{
    protected $fillable = [
        'idLeague', 'strLeague', 'strCountry'
    ];
}
