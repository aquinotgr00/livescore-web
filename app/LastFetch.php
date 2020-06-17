<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LastFetch extends Model
{
    protected $table = 'last_fetchs';

    protected $fillable = [
        'date', 'route'
    ];
}
