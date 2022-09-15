<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SignInOut extends Model
{
    use HasFactory;

    protected $table = 'signinout';

    protected $fillable = [
        'USERID',
        'EVENTDATE',
        'SIGNIN_TIME',
        'CREATEDSIGNIN_DATE',
        'CREATEDSIGNIN_TIME',
        'SIGNOUT_TIME',
        'CREATEDSIGNOUT_DATE',
        'CREATEDSIGNOUT_TIME',
        'TotalMins',
        'TotalTaskMins',
    ];

    protected $appends = [
        'tminsformated',
        'ttaskminsformatted',
        'name',
        // 'totalmins',
        // 'totaltaskmins',
    ];
    public function getsignout()
    {

        $snout= DB::table('sign_in_outs')
            ->join('users', 'sign_in_outs.UserID', '=', 'users.ID')
            ->select('sign_in_outs.ID', 'sign_in_outs.UserID', 'sign_in_outs.Date', 'sign_in_outs.Time', 'sign_in_outs.SignIn', 'sign_in_outs.SignOut', 'users.name')
            ->get();
        return $snout;
    }
    public function getTminsformatedAttribute()
    {
        //$tminsformatted = gmdate('H:i:s', $data->first()->TotalMins);
        // return gmdate('H:i:s', $this->TotalMins);
        date_default_timezone_set('utc');
        return date('H:i:s', $this->TotalMins * 60);
    }
    public function getTtaskminsformattedAttribute()
    {
        date_default_timezone_set('utc');
        return date('H:i:s', $this->TotalTaskMins * 60);
    }
    public function getNameAttribute()
    {
        //join users table and get name
        $name = DB::table('users')
            ->select('Name')
            ->where('ID', $this->USERID)
            ->get();
        return $name->first()->Name;
    }
    // public function getTotalminsAttribute()
    // {

    // }
    // public function getTotaltaskminsAttribute()
    // {
    //     return $this->TotalTaskMins;
    // }



}
