<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
     protected $fillable = [
        'date_added', 'download_path', 'merchant_id', 'created_at'
    ];
}
