<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Models\User;

use App\Models\Userhourlyrate;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use Auth;

use Illuminate\Support\Facades\Auth as FacadesAuth;

use Illuminate\Support\Facades\DB;

use App\Traits\ApiResponser;



class UserController extends Controller

{

    use ApiResponser;

    public function __construct()
    {

        \config()->set('auth.defaults.guard', 'api');
    }

    public function login(Request $request)
    {
        try {
            $user = User::where('Email', $request->email)->first();

            $validator = validator()->make($request->all(), [

                'email' => 'required|email',

                'password' => 'required',

            ]);
            $check = User::where('email', $request->email)->where('UserPwd', $request->password)->first();

            if ($validator->fails()) {
                return $this->error($validator->errors(), 401);
            }
           if ($check == null) {
                return $this->error('Email or password is incorrect', 401);
            }

            if (!$token = (auth()->login($check)  )) {
                return $this->error('Unauthorized', 401);

            } else {

                //$user = FacadesAuth::user();
                return $this->success([

                    'user' => $user,

                    'token' => $token,

                    //'token2' =>$user->createToken("API TOKEN")->plainTextToken

                ], 'Login Successful', 200);
            }
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 500);
        }
    }
    // public function register(Request $request)
    // {
    //     try {
    //         $validator = validator()->make($request->all(), [
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|string|email|max:255|unique:users',
    //             'password' => 'required|string|min:6',
    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 401);
    //         }
    //         $user = User::create(array_merge(
    //             $validator->validated(),
    //             ['password' => bcrypt($request->password)]
    //         ));
    //         return $this->success([
    //             'user' => $user,
    //         ], 'Registration Successful', 200);
    //     } catch (\Throwable $e) {
    //         return $this->error($e->getMessage(), 500);
    //     }
    // }

    public function logout()
    {
        auth()->logout();

        return $this->success(['message' => 'Successfully logged out'], 'Logout Successful', 200);
    }
    public function userProfile()
    {

        return response()->json(auth()->user());
    }
    protected function createNewToken($token)
    {

        return response()->json([

            'access_token' => $token,

            'token_type' => 'bearer',

            'expires_in' => strtotime(date('Y-m-d H:i:s', strtotime('+60 min'))) - time(),

            'user' => auth()->user()

        ]);
    }

    public function getuserbyid()
    {
        try {

            $userid =  Request('ID');

            // $month = Request('month');

            // $year = Request('year');

            $getuser = User::LEFTJOIN('userhourlyrate', 'users.ID', '=', 'userhourlyrate.USERID')
                // ->wheremonth('users.created_at', $month)
                // ->whereyear('users.created_at', $year)
                ->where('users.ID', $userid)
                ->select('users.*', 'userhourlyrate.HourlyINR', 'userhourlyrate.MonthID', 'userhourlyrate.YearID', 'userhourlyrate.Salary', 'userhourlyrate.OverHead')
                ->get();
            if ($getuser->count() > 0) {
                return $this->success($getuser, 'User Information(s) retrieved successfully');
            } else {
                return $this->success($getuser, 'No Information retrieved');
            }
        } catch (\Throwable $e) {

            return $this->error($e->getMessage(), 500);
        }
    }
}
