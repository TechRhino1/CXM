<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignInOutRequest;
use App\Models\SignInOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use Illuminate\Console\View\Components\Alert;

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
            $signInOuts = SignInOut::all();
            $count = $signInOuts->count();
            return $this->success($signInOuts, message: ' A total of '.$count.' Leave Information(s) retrieved', status: 200);
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
            $UserID = auth()->user()->id;
            $data = SignInOut::where('user_id', $UserID)->where('EVENTDATE', date('Y-m-d'))->get();
            if ($data->count() > 0) {
                return $this->error('You allready signed in today you cannot sign in again please contact your organization', 400);
            } else {
                $signInOut = SignInOut::create(
                    [
                        'user_id' => $UserID,
                        'EVENTDATE' => date('Y-m-d'),
                        'SIGNIN_TIME' => date('H:i:s'),
                        'CREATEDSIGNIN_DATE' => date('Y-m-d'),
                        'CREATEDSIGNIN_TIME' => date('H:i:s'),
                        'TotalMins' => 0,
                        'TotalTaskMins' => 0,

                    ]
                );
                if ($signInOut) {
                    return $this->success($signInOut, 'Sign In created successfully', 201);
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

                $UserID = auth()->user()->id;
                $data = SignInOut::where('user_id', $UserID)->where('EVENTDATE', date('Y-m-d'))->get();
                if ($data->count() <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You need to sign in first'
                    ], 200);
                } elseif ($data->count() > 0) {
                    $SIGNOUT_TIME = date('H:i:s');
                    $SIGNIN_TIME = $data->first()->SIGNIN_TIME;
                    $TotalMins = (strtotime($SIGNOUT_TIME) - strtotime($SIGNIN_TIME)) / 60;

                    $signInOut = SignInOut::where('user_id', $UserID)->where('EVENTDATE', date('Y-m-d'))->update(
                        [
                            'SIGNOUT_TIME' => date('H:i:s'),
                            'CREATEDSIGNOUT_DATE' => date('Y-m-d'),
                            'CREATEDSIGNOUT_TIME' => date('H:i:s'),
                            'TotalMins' =>  $TotalMins,
                        ]
                    );


                    if ($signInOut) {
                        return $this->success('Sign Out successfully', 201);
                    } else {
                        return $this->error('Sign Out failed',400);
                    }
                } else {
                    return $this->error('You allready signed out today you cannot sign out again please contact your organization', 401);
                }
            } elseif (auth()->user()->role == '0') { //only admin can update sign in out

                $UserID = $request->user_id;
                $signInOut->update(
                    [
                        'user_id' => $UserID,
                        'EVENTDATE' => date('Y-m-d'),
                        'SIGNIN_TIME' => date('H:i:s'),
                        'CREATEDSIGNIN_DATE' => date('Y-m-d'),
                        'CREATEDSIGNIN_TIME' => date('H:i:s'),
                        // 'SIGNOUT_TIME' => date('H:i:s'),
                        // 'UPDATEDSIGNOUT_DATE' => date('Y-m-d'),
                        // 'UPDATEDSIGNOUT_TIME' => date('H:i:s'),
                        // 'TotalMins' =>  $TotalMins,
                    ]
                );
                return response()->json([
                    'success' => true,
                    'message' => 'Sign In updated successfully by ' . auth()->user()->name,

                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this sign in out'
                ], 200);
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
            $UserID = auth()->user()->id;
            $data = SignInOut::where('user_id', $UserID)->whereMonth('EVENTDATE', $month)->whereYear('EVENTDATE', $year)->get();

            if ($data->count() > 0) {
                $count = $data->count();
                return $this->success($data , 'A total of '.$count.' Information(s) retrieved');
            } else {
                return $this->success($data , 'No Information(s) retrieved');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }


    }
    public function getusersigndetails()
    {
        //get details of all user in current day
        try {
            //join users table and sign in out table to get only user name
            $data = SignInOut::join('users', 'users.id', '=', 'sign_in_outs.user_id')->where('sign_in_outs.EVENTDATE', date('Y-m-d'))->Select('users.name', 'sign_in_outs.SIGNIN_TIME', 'sign_in_outs.SIGNOUT_TIME', 'sign_in_outs.TotalMins','sign_in_outs.EVENTDATE','sign_in_outs.TotalTaskMins','sign_in_outs.user_id')->get();
            if ($data->count() > 0) {
                return $this->success($data, 'A total of ' . $data->count() . ' Information(s) retrieved');
            } else {
                return $this->success ($data, 'No Information(s) retrieved');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
