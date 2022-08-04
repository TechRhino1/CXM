<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignInOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'EVENTDATE',
        'SIGNIN_TIME',
        'CREATEDSIGNIN_DATE',
        'CREATEDSIGNIN_TIME',
        'SIGNOUT_TIME',
        'CREATEDSIGNOUT_DATE',
        'CREATEDSIGNOUT_TIME',
        'TotalMins',
        'TotalTaskMins',
    ];

    
}
