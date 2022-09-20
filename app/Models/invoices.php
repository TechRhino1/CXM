<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoices extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'date_created',
        'user_created',
        'invoice_date',
        'client_id',
        'currency',
        'amount',
        'status',
        'amount_received',
        'conversion_rate',
        'date_received',
    ];
}
