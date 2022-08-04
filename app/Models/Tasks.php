<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'Title',
        'Description',
        'ProjectID',
        'CreaterID',
        'EstimatedDate',
        'EstimatedTime',
        'Priority',
        'CurrentStatus',
        'InitiallyAssignedToID',
        'CurrentlyAssignedToID',
        'CompletedDate',
        'CompletedTime',
        'ParentID',
    ];

}
