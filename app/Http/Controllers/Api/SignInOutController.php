<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignInOutRequest;
use App\Models\SignInOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
       // print_r($user->id);
        $data = SignInOut::where('EVENTDATE', date('Y-m-d') )->where('user_id', $user->id)->first();
       
        print_r($data);
       // print_r(Auth::user());
        die();

        if($data > 0){
            return response()->json([ 'message' => 'You have already signed in Today .You cannot do it again '], 422); 
            } else { //if user not signed in,Today
                $signInOut = SignInOut::create($request->all());
                if($signInOut) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Sign In created successfully',
                            'signInOut' => $signInOut
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Sign In failed'
                        ], 400);
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
        
       //only admin can update sign in out

        if($request->user()->role == 'admin'|| $request->user()->role == '1'){
            $signInOut->update($request->all());
            return response()->json(['success' => 'You have successfully updated sign in out'], 200);
        }else{
            return response()->json(['error' => 'You are not authorized to update sign in out'], 400);
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
