<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class companies extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'logo',
        'bank_account_name',
        'bank_account_number',
        'bank_account_type',
        'bank_account_branch',
        'bank_address',
        'bank_account_ifsc',
        'bank_account_swiftcode',
        'invoice_header',
        'invoice_company_details',
        'invoice_sub_header_left',
        'invoice_sub_header_right',
        'invoice_footer',
        'template_content',
    ];


}
