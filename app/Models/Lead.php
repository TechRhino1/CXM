<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'leads';

    protected $fillable = [
        'companies_id',
        'name',
        'email',
        'phone',
        'date_created',
        'date_last_followup',
        'date_next_followup',
    ];
}
