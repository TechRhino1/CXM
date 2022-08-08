<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignInOutRequest;
use App\Models\SignInOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SignInOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        //if user allready signed in,then show error message you allready signed in today you can't sign in again
        // print_r($data->get()->count());
        // print_r($data->get()->count() > 0);
        // print_r(date('Y-m-d'));
       //get user id from token
        $UserID = auth()-> user()-> id;
        $data = SignInOut::where('user_id',$UserID)->where('EVENTDATE',date('Y-m-d'))->get(); 
        if($data->count() > 0){
            return response()->json([
                'success' => false,
                'message' => 'You allready signed in today you can\'t sign in again'
            ], 200);
        }
        else{
            $signInOut = SignInOut::create(
                [
                    'user_id' => $UserID,
                    'EVENTDATE' => date('Y-m-d'),
                    'SIGNIN_TIME' => date('H:i:s'),
                    'CREATEDSIGNIN_DATE' => date('Y-m-d'),
                    'CREATEDSIGNIN_TIME' => date('H:i:s'),
                    // 'SignInStatus' => '1',
                    // 'SignOutStatus' => '0',
                ]
            );
            if($signInOut) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sign In Out created successfully',
                    'signInOut' => $signInOut
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                ], 200);
            }
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
      //check if user allready signed in

        $UserID = auth()-> user()-> id;
        $data = SignInOut::where('user_id',$UserID)->where('EVENTDATE',date('Y-m-d'))->get();
        if(!$data->count() > 0){
            return response()->json([
                'success' => false,
                'message' => 'You need to sign in first'
            ], 200);
    }elseif($request->SIGNOUT_TIME == '00:00'){
        //TotalMins =     (SIGNOUT_TIME - SIGNIN_TIME) / 60
        $TotalMins = (strtotime($request->SIGNOUT_TIME) - strtotime($request->SIGNIN_TIME)) / 60;
        $signInOut->SIGNOUT_TIME = date('H:i:s');
        $signInOut->TOTAL_MINS = $TotalMins;
        $signInOut->SIGNOUT_DATE = date('Y-m-d');
      
        $signInOut->update(
            [
                'SIGNOUT_TIME' => date('H:i:s'),
                'UPDATEDSIGNOUT_DATE' => date('Y-m-d'),
                'UPDATEDSIGNOUT_TIME' => date('H:i:s'),
                'TotalMins' =>  $TotalMins,
                
              
            ]
        );
        if($signInOut) {
            return response()->json([
                'success' => true,
                'message' => 'Sign Out successfully',
                'signInOut' => $signInOut
            ], 200);
        } else {
            return response()->json([
                'success' => false,
            ], 200);
        }



        
       //only admin can update sign in out

        if(auth()->user()->role == 'admin' || auth()->user()->role == '0'){
            $signInOut->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Sign In updated successfully',
                'signInOut' => $signInOut
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this sign in out'
            ], 200);
        }

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
       
        //only admin can delete sign in out
        if($request->user()->role === 'admin' || $request->user()->role === '1'){
            $signInOut->delete();
            return response()->json(['success' => 'You have successfully deleted your sign in out.'], 200);
        }else{
            return response()->json(['error' => 'You are not authorized to delete this sign in out.'], 400);
        }

    }


}
