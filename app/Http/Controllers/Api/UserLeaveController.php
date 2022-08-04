<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserLeavesRequest;
use App\Models\UserLeaves;
use Illuminate\Http\Request;

class UserLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userLeaves = UserLeaves::all();
        return response()->json($userLeaves);
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
    public function store(StoreUserLeavesRequest $request)
    {
        $userLeave = UserLeaves::create($request->all());
        if($userLeave) {
            return response()->json([
                'success' => true,
                'message' => 'User Leave created successfully',
                'userLeave' => $userLeave
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Leave creation failed'
            ], 400);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserLeaves  $userLeaves
     * @return \Illuminate\Http\Response
     */
    public function show(UserLeaves $userLeaves)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserLeaves  $userLeaves
     * @return \Illuminate\Http\Response
     */
    public function edit(UserLeaves $userLeaves)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserLeaves  $userLeaves
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserLeaves $userLeaves)
    {
        $userLeaves = UserLeaves::find($request->id);

        if($userLeaves->update($request->all())) {
            return response()->json([
                'success' => true,
                'message' => 'User Leave updated successfully',
                'userLeave' => $userLeaves
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Leave update failed'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserLeaves  $userLeaves
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userLeave = UserLeaves::find($id);
        if($userLeave->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'User Leave deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Leave deletion failed'
            ], 400);
        }
    }
    public function userleaveprofile($id)
    {
        if($id !== null) {
            $userLeave = UserLeaves::find($id);
            return response()->json($userLeave);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Leave not found',
            ], 400);
        }
        
    }
}