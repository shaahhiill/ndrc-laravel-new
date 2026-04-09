<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'endpoint',
        'request_data',
        'response_code',
    ];
}
