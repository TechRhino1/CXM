<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userhourlyrate extends Model
{
    use HasFactory;
  
  protected $table = 'userhourlyrate';

    protected $fillable = [
        'UserID', 'HourlyRate', 'MonthID', 'YearID', 'Salary', 'OverHead'
    ];
}
