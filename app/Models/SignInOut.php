<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SignInOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
    ];
    public function getsignout()
    {

        $snout= DB::table('sign_in_outs')
            ->join('users', 'sign_in_outs.UserID', '=', 'users.id')
            ->select('sign_in_outs.id', 'sign_in_outs.UserID', 'sign_in_outs.Date', 'sign_in_outs.Time', 'sign_in_outs.SignIn', 'sign_in_outs.SignOut', 'users.name')
            ->get();
        return $snout;
    }
    public function getTminsformatedAttribute()
    {
        //$tminsformatted = gmdate('H:i:s', $data->first()->TotalMins);
        // return gmdate('H:i:s', $this->TotalMins);
        return date('H:i:s', $this->TotalMins * 60);
    }
    public function getTtaskminsformattedAttribute()
    {
        return date('H:i:s', $this->TotalTaskMins * 60);
    }


}
