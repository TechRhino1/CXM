<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        \config()->set('auth.defaults.guard', 'api');
    }

    public function login(Request $request)
    {
        $json = $request->json()->all();
        $user = DB::table('users')->select('role')->where('email', $json['email'])->first();

       $validator = validator()->make($json, [
            'email' => 'required|email',
            'password' => 'required | string | min:6',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        
        if (! $token = auth('api')->attempt($validator->validated())) {
            return response()->json([ 
                'status' => 'error',
                'message' => 'Unauthorized',
             ], 401);
        }
        // return $this->respondWithToken($token);
       $user = FacadesAuth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
    }

    public function register(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6',
            
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]

            
        ));

        return response()->json([
            
            'message' => 'Created successfully',
            ' User' => $user,
    
    
        ], 201);
    }

    public function logout()
    {
        auth()->logout();
        
        return response()->json(['message' => 'Successfully logged out']);
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
}
