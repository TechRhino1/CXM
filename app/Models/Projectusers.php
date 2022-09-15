<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projectusers extends Model
{
    use HasFactory;

    protected $table = 'projectusers';
    protected $fillable = ['ProjectID', 'UserID'];


}
