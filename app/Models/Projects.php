<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $fillable = [
        'Description',
        'Billing',
        'BillingType',
        'TotalHours',
        'Status',
        'HourlyINR',
        'Currency',
        'StartDate',
        'EndDate',
        'TotalClientHours',
        'UserID',
        'Comments',
        'InternalComments',
        'ClientID',
    ];
}
