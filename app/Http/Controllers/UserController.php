<?php

namespace App\Http\Controllers;

use App\Models\User;
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

            $user = User::where('email', $request->email)->first();
            $validator = validator()->make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors(), 401);
            }

            if (!$token = auth('api')->attempt($validator->validated())) {
                return $this->error('Unauthorized', 401);
            } else {
                // $user = FacadesAuth::user();
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

    public function register(Request $request)
    {
        try {
            $validator = validator()->make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required',


            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));

            return $this->success([
                'user' => $user,
            ], 'Registration Successful', 200);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
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

            $id = Request('id');
            $month = Request('month');
            $year = Request('year');

            $getuser = User::where('UserID', $id)->whereMonth('DateFrom', $month)->whereYear('DateFrom', $year)->get();
            return $this->success($getuser, 'A total of ' . $getuser->count() . ' Leave Information(s) retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
