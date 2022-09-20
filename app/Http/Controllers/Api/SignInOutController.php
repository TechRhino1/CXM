<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreSignInOutRequest;

use App\Models\SignInOut;

use App\Models\User;

use App\Models\Userhourlyrate;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Traits\ApiResponser;

use Illuminate\Console\View\Components\Alert;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class SignInOutController extends Controller

{

    use ApiResponser;

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        try {
            $month = Request('month');
            $year = Request('year');

            $signInOuts = SignInOut::whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->orderby('EVENTDATE', 'desc')
            ->get();

            $count = $signInOuts->count();

            return $this->success($signInOuts, message: ' A total of ' . $count . ' Leave Information(s) retrieved', status: 200);
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 400);
        }
    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        //

    }
    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(StoreSignInOutRequest $request)

    {

        try {

            $UserID = auth()->user()->ID;

            $data = SignInOut::where('USERID', $UserID)->where('EVENTDATE', date('Y-m-d'))->get();



            if ($data->count() > 0) {

                return $this->success($data, 'You already signed in today at ' . $data[0]->SIGNIN_TIME . ' you cannot sign in again please contact your organization', 200);
            } else {

                $signInOut = SignInOut::create(

                    [

                        'USERID' => $UserID,

                        'EVENTDATE' => date('Y-m-d'),

                        'SIGNIN_TIME' => date('H:i'),

                        'SIGNOUT_TIME' => '00:00',

                        'CREATEDSIGNIN_DATE' => date('Y-m-d'),

                        'CREATEDSIGNIN_TIME' => date('H:i:s'),

                        'TotalMins' => 0,

                        'TotalTaskMins' => 0,
                    ]

                );

                if ($signInOut) {

                    return $this->success([$signInOut], 'You have successfully signed in', 200);
                } else {

                    return $this->error('Sign In creation failed', 400);
                }
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }



    /**

     * Display the specified resource.

     *

     * @param  \App\Models\SignInOut  $signInOut

     * @return \Illuminate\Http\Response

     */

    public function show(SignInOut $signInOut)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Models\SignInOut  $signInOut

     * @return \Illuminate\Http\Response

     */

    public function edit(SignInOut $signInOut)

    {

        //

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\SignInOut  $signInOut

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, SignInOut $signInOut)

    {

        try {

            if (auth()->user()->role != '0') {



                $UserID = auth()->user()->ID;

                $data = SignInOut::where('USERID', $UserID)->where('EVENTDATE', date('Y-m-d'))->get();

                if ($data->count() <= 0) {

                    return response()->json([

                        'success' => false,

                        'message' => 'You need to sign in first'

                    ], 200);
                } elseif ($data->count() > 0) {

                    $SIGNOUT_TIME = date('H:i:s');

                    $SIGNIN_TIME = $data->first()->SIGNIN_TIME;

                    $TotalMins = (strtotime($SIGNOUT_TIME) - strtotime($SIGNIN_TIME)) / 60;

                    $signInOut = SignInOut::where('USERID', $UserID)->where('EVENTDATE', date('Y-m-d'))->update(

                        [

                            'SIGNOUT_TIME' => date('H:i'),

                            'CREATEDSIGNOUT_DATE' => date('Y-m-d'),

                            'CREATEDSIGNOUT_TIME' => date('H:i:s'),

                            'TotalMins' =>  $TotalMins,

                        ]

                    );

                    $signInOut = [

                        'SIGNIN_TIME' => $data->first()->SIGNIN_TIME,

                        'SIGNOUT_TIME' => date('H:i'),

                        'CREATEDSIGNIN_DATE' => $data->first()->CREATEDSIGNIN_DATE,

                        'CREATEDSIGNIN_TIME' => $data->first()->CREATEDSIGNIN_TIME,

                        'CREATEDSIGNOUT_DATE' => date('Y-m-d'),

                        'CREATEDSIGNOUT_TIME' => date('H:i:s'),

                        'TotalMins' => $TotalMins,

                    ];

                    if ($signInOut) {

                        return $this->success([$signInOut], 'Sign Out created successfully', 201);
                    } else {

                        return $this->error('Sign Out failed', 400);
                    }
                } else {

                    return $this->error('You already signed out today you cannot sign out again please contact your organization', 401);
                }
            } elseif (auth()->user()->role == '0') { //only admin can update sign in out

                $UserID = $request->USERID;

                $signInOut->update(

                    [

                        'USERID' => $UserID,

                        'EVENTDATE' => date('Y-m-d'),

                        'SIGNIN_TIME' => date('H:i'),

                        'CREATEDSIGNIN_DATE' => date('Y-m-d'),

                        'CREATEDSIGNIN_TIME' => date('H:i:s'),

                    ]

                );

                return $this->success($signInOut, 'Sign In updated successfully by ' . auth()->user()->name, 201);
            } else {

                return $this->error('You are not authorized to update sign in out', 401);
            }
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 400);
        }
    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Models\SignInOut  $signInOut

     * @return \Illuminate\Http\Response

     */

    public function destroy(SignInOut $signInOut, Request $request)

    {

        try {

            //only admin can delete sign in out

            if ($request->user()->role === '1') {

                $signInOut->delete();

                return $this->success('Sign In deleted successfully', $signInOut, 200);
            } else {

                return $this->error('You are not authorized to delete this sign in out', 401);
            }
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 400);
        }
    }

    public function getsignioutbyuserid()

    { //get sign in out by user id in given month and year

        try {

            $month = request('month');

            $year = request('year');

            $UserID = request('user');

            $data = SignInOut::where('USERID', $UserID)->whereMonth('EVENTDATE', $month)->whereYear('EVENTDATE', $year)
            ->orderBy('EVENTDATE', 'DESC')
            ->get();

            if ($data->count() > 0) {

                $count = $data->count();

                return $this->success($data, 'A total of ' . $count . ' Information(s) retrieved');
            } else {

                return $this->success($data, 'No Information(s) retrieved');
            }
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 400);
        }
    }

    public function getusersigndetails()

    {

        //get details of all user in current day

        try {
            $data = User::Select('users.name', 'signinout.SIGNIN_TIME', 'signinout.SIGNOUT_TIME', 'signinout.TotalMins', 'signinout.EVENTDATE', 'signinout.TotalTaskMins', 'signinout.USERID')
                ->leftJoin("signinout", function ($join) {
                    $join->on('signinout.USERID', '=', 'users.ID')
                        ->where('signinout.EVENTDATE', '=', date('Y-m-d'));
                })
                //->OrderBy('signinout.SIGNIN_TIME', 'ASC')
                ->get();

            if ($data->count() > 0) {

                return $this->success($data, 'A total of ' . $data->count() . ' Information(s) retrieved');
            } else {

                return $this->success($data, 'No Information(s) retrieved');
            }
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 400);
        }
    }

    public function getcurrentsigninout()

    {

        //get current sign in out of user

        try {

            $UserID = auth()->user()->ID;

            $date = Request('date');

            $data = SignInOut::where('USERID', $UserID)->where('EVENTDATE', $date)
            ->orderby('EVENTDATE', 'desc')
            ->get();

            if ($data->count() > 0) {

                return $this->success($data, 'A total of ' . $data->count() . ' Information(s) retrieved');
            } else {

                return $this->success($data, 'No Information(s) retrieved');
            }
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 400);
        }
    }

    //for admins

    public function register(Request $request)

    {

        try {

            $validator = validator()->make($request->all(), [

                'Name' => 'required|string|max:255',

                'Email' => 'required|string|email|max:255|unique:users',

                'UserPwd' => 'required|string|min:6',

                'role' => 'required',

            ]);

            if ($validator->fails()) {

                return response()->json(['error' => $validator->errors()], 401);
            }

            $user = User::create(array_merge(

                $validator->validated(),

                //['UserPwd' => $request->UserPwd]

                ['UserPwd' => $request->UserPwd]

            ));

            $_hourlyinr = request('HourlyINR');

            $_hourlyoverhead = request('OverHead');

            $_monthid = request('MonthID');

            // $_monthid = intval($_monthid);

            $_yearid = request('YearID');

            $_salary = request('Salary');

            $getuserid = User::where('Email', $request->Email)->first();

            //SELECT ID FROM userhourlyrate WHERE USERID=$SQL_ID AND MONTHID=$_monthid AND YEARID=$_yearid

            $GETuserhourly = Userhourlyrate::where('UserID',  $getuserid->ID)->where('MonthID', $_monthid)->where('YearID', $_yearid)->first();

            $userhourlyrateid = 0;

            if ($GETuserhourly == null) {

                //INSERT INTO userhourlyrate (USERID, HOURLYINR, MONTHID, YEARID, SALARY, OVERHEAD) VALUES

                $insertuserhourly = Userhourlyrate::create(array_merge(

                    $validator->validated(),

                    ['UserID' => $getuserid->ID, 'HourlyINR' => $_hourlyinr, 'MonthID' => $_monthid, 'YearID' => $_yearid, 'Salary' => $_salary, 'OverHead' => $_hourlyoverhead]

                ));

                return $this->success($insertuserhourly, 'Registration Successful');
            } else {

                $userhourlyrateid = $GETuserhourly->ID;

                //UPDATE userhourlyrate set HOURLYINR=$_hourlyinr, MONTHID=$_monthid, YEARID=$_yearid, SALARY=$_salary, OVERHEAD=$_hourlyoverhead WHERE ID=$userhourlyrateid

                $updateuserhourly = UserHourlyRate::where('ID', $userhourlyrateid)->update(array_merge(

                    $validator->validated(),

                    ['HourlyINR' => $_hourlyinr, 'MONTHID' => $_monthid, 'YEARID' => $_yearid, 'SALARY' => $_salary, 'OVERHEAD' => $_hourlyoverhead]

                ));
                return $this->success($updateuserhourly, 'User / Rates updated successfully.');
            }
            return $this->success([

                'user' => $user,

            ], 'Registration Successful', 200);
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 500);
        }
    }

    public function getusersdata()
    {
        try {
            // SELECT u.NAME, u.ID, u.USERTYPE, u.EMAIL, IFNULL(uh.HOURLYINR,0) as HOURLYINR, IFNULL(uh.MONTHID,0) as MONTHID, IFNULL(uh.YEARID,0) as YEARID, IFNULL(uh.SALARY,0) as SALARY, IFNULL(uh.OVERHEAD,0) as OVERHEAD FROM users u LEFT JOIN userhourlyrate uh ON (u.ID = uh.USERID AND uh.MONTHID=$_month AND uh.YEARID=$_year) ORDER BY u.NAME
            $data = User::select('users.*', 'userhourlyrate.HourlyINR', 'userhourlyrate.MonthID', 'userhourlyrate.YearID', 'userhourlyrate.Salary', 'userhourlyrate.OverHead', 'userhourlyrate.UserID')
                ->leftJoin('userhourlyrate', function ($join) {
                    $join->on('users.ID', '=', 'userhourlyrate.UserID')
                        ->where('userhourlyrate.YEARID', '=', request('year'))
                        ->where('userhourlyrate.MONTHID', '=', request('month'));
                })
                ->OrderBy('users.Name')->get();

            foreach ($data as $usertype) {
                $usertype->role = ($usertype->role == 0 ? 'Manager' : ($usertype->role == 1 ? 'Developer' : ($usertype->role == 2 ? 'Sales' : 'Clients')));
            }


            if ($data->count() > 0) {

                return $this->success($data, 'A total of ' . $data->count() . ' Information(s) retrieved');
            } else {

                return $this->success($data, 'No Information(s) retrieved');
            }
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 400);
        }
    }
    public function getusers()
    {
        try {
            $data = User::LEFTJOIN('userhourlyrate', 'users.ID', '=', 'userhourlyrate.UserID')
                ->select('users.*', 'userhourlyrate.HourlyINR', 'userhourlyrate.MonthID', 'userhourlyrate.YearID', 'userhourlyrate.Salary', 'userhourlyrate.OverHead', 'userhourlyrate.UserID')
                ->orderBy('users.Name', 'Asc')
                ->get();
            foreach ($data as $usertype) {
                $usertype->role = ($usertype->role == 0 ? 'Manager' : ($usertype->role == 1 ? 'Developer' : ($usertype->role == 2 ? 'Sales' : 'Clients')));
            }


            if ($data->count() > 0) {

                return $this->success($data, 'A total of ' . $data->count() . ' Information(s) retrieved');
            } else {

                return $this->success($data, 'No Information(s) retrieved');
            }
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 400);
        }
    }
    public function updateuser(Request $request)
    {
        try{
            $validator = validator()->make($request->all(), [
                'ID' => 'required',
                'Name' => 'required',
                'Email' => 'required',
                'role' => 'required',
                'HourlyINR' => 'required',
                'MonthID' => 'required',
                'Salary' => 'required',
                'OverHead' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), 400);
            }
        $update = User::where('ID', $request->ID)
            ->update([
                'Name' => $request->Name,
                'Email' => $request->Email,
                'role' => $request->role,
            ]);
            if($request->UserPwd != null){
                $update = User::where('ID', $request->ID)
                ->update([
                    'UserPwd' =>$request->UserPwd,
                ]);
            }
        $update = UserHourlyRate::where('UserID', $request->ID)
            ->update([
                'HourlyINR' => $request->HourlyINR,
                'MonthID' => $request->MonthID,
                'YearID' => $request->YearID,
                'Salary' => $request->Salary,
                'OverHead' => $request->OverHead,
            ]);
        return $this->success($update, 'User updated successfully.');

        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 400);
        }
    }
}
