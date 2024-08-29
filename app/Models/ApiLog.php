<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $table = 'api_log';

    protected $primaryKey = 'id';

    protected $fillable = [
        'input_date',
        'input_json',
        'response_date',
        'response_json',
        'response_code',
        'url',
        'account_token'
    ];
}
