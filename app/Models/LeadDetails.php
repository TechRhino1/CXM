<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadDetails extends Model
{
    use HasFactory;

    protected $table = 'lead_details';

    protected $fillable = [
        'leads_id',
        'date_created',
        'comments',
    ];
}
