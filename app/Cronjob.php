<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cronjob extends Model
{
    protected $fillable = [
        'query', 'time', 'routine_gap', 'routine_day', 'user_id','file_type'
    ];
}
