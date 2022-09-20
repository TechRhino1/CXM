<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice_details extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'task_id',
        'project_id',
        'updated_comments',
        'updated_time',
    ];
}
