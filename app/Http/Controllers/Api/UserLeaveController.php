<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserLeavesRequest;
use App\Models\UserLeaves;
use Error;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
class UserLeaveController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
        $userLeaves = UserLeaves::all();
        return $this->success($userLeaves ,'User Leave data List');
        }catch(\Throwable $e){
            return $this->error($e->getMessage(), 500);
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
    public function store(StoreUserLeavesRequest $request)
    {
        try{
        $UserID = auth()->user()->id;
        
        $userLeave = UserLeaves::create(
            [
                'UserID' => $UserID,
                'DateFrom' => $request->DateFrom,
                'DateTo' => $request->DateTo,
                'Reason' => $request->Reason,
                'AppurvalStatus' => $request->ApprovalStatus,

            ]
        );
        if($userLeave) {
            return $this->success($userLeave,'User Leave Created Successfully', 201);
        } else {
            return  $this->error('User Leave Not Created', 500);
        }

        }catch(\Throwable $e ){
            return $this-> error($e->getMessage(),400);
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
    public function update(StoreUserLeavesRequest $request, UserLeaves $userLeaves ,$id )
    {
       try{
         $userId = auth()->user()->id;
        
            $userLeave = UserLeaves::where('id',$id)->where('UserID',$userId)->first();
            if($userId ==  $userLeave->UserID ){
                if($userLeave->update($request->all())){
                    return $this->success([
                        'message' => 'User Leave updated successfully',
                        'userLeave' => $userLeave
                    ],200);
                }
                return $this->error('User Leave not updated',500);
            }
            else{
                return $this->error('You are not authorized to update this leave', 500);
            }


       
        
           
        }
        catch(\Throwable $e ){
            return $this->error($e->getMessage(),400);
        }
    }
   
    ///for admins
    // $userLeaves->update([
    //     'ApprovalStatus' => $request->ApprovalStatus,
    //     'ApprovedUserID' => $userId,
    //     'ApprovedDate' => date('Y-m-d H:i:s'),
    //     'ApprovalComments' => $request->ApprovalComments]);

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserLeaves  $userLeaves
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
        $userLeave = UserLeaves::find($id);
        if($userLeave->delete()) {
            return $this->success($userLeave,'User Leave Deleted Successfully', 201);
        } else {
            return $this->error('User Leave Not Deleted', 500);
        }
    }
    catch(\Throwable $e ){
        return $this->error($e->getMessage(),400);
    }
    }
    public function userleaveprofile($id)
    {
        try{
        if($id !== null) {
            $userLeave = UserLeaves::where('UserID', $id)->get();
            return $this->success($userLeave,'User Leave Profile', 201);
        } else {
            return $this->error('User Leave Not Found', 404);
        }
        
    }
    catch(\Throwable $e ){
        return $this->error($e->getMessage(),400);
    }
    }
}
