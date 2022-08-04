<?php

namespace App\Http\Controllers;

use App\Models\Subadmin;
use Illuminate\Http\Request;

class SubAdminController extends Controller
{
    public function __construct()
    {
        \config()->set('auth.defaults.guard', 'subadmin-api');
    }

    public function login(Request $request)
    {
       $validator = validator()->make($request->all(), [
            'email' => 'required|email',
            'password' => 'required | string | min:6',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        
        $credentials = request(['email', 'password']);
        
        if (! $token = auth('admin-api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:subadmins',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        
        $admin = Subadmin::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            
            'message' => 'SubAdmin created successfully',
            'admin' => $admin,
    
    
        ], 201);

        
        // $token = auth('admin-api')->login($admin);
        
        // return $this->respondWithToken($token);
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