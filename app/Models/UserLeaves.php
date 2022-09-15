<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserLeaves extends Model
{
    use HasFactory;

    protected $table = 'userleaves';
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
    protected $appends = [

        'name',

    ];
    public function getNameAttribute()
    {
        //join users table and get name
        $name = DB::table('users')
            ->select('Name')
            ->where('ID', $this->UserID)
            ->get();
        return $name->first()->Name;
    }


}
