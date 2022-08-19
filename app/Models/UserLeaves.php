<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLeaves extends Model
{
    use HasFactory;
    protected $fillable = [
        'UserID',
        'DateFrom',
        'DateTo',
        'Reason',
        'ApprovalStatus',
        'ApprovedUserID',
        'ApprovedDate',
        'ApprovalComments',
    ];



}
