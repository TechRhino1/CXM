<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserLeavesRequest;
use App\Models\UserLeaves;
use App\Models\User;
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

        $userLeaves = UserLeaves::where('DateFrom', '>=', date('Y-m-d'))
        ->orderby('DateFrom', 'desc')
        ->get();
        foreach($userLeaves as $userLeave){
            $userLeave->ApprovalStatus =($userLeave->ApprovalStatus == 0 ? 'Awaiting Approval' : ($userLeave->ApprovalStatus == 1 ? 'Approved' : 'Rejected'));
        }
        // $userLeaves = UserLeaves::all();
        $count = $userLeaves->count();
        return $this->success($userLeaves ,'A total of '.$count.' Leave Information(s) retrieved successfully');
        }catch(\Throwable $e){
            return $this->error($e->getMessage(), 500);
        }
    }
    // 'approveddate' => ($userLeaves->ApprovedDate  == '0000-00-00 00:00:00' ? '' : $userLeaves->ApprovedDate),
    // 'approvalcomments' =>$userLeaves->ApprovalComments,
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
         $userId = Request('UserID');

            $userLeave = UserLeaves::where('id',$id)->where('UserID',$userId)->first();
            if($userId ==  $userLeave->UserID ){
                if($userLeave->update($request->all())){
                    return $this->success($userLeave,'User Leave Updated Successfully', 201);
                }
                return $this->error('User Leave not updated',400);
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

         $userLeaves = UserLeaves::join ('users','users.id','=','user_leaves.UserID') ->where('user_leaves.UserID',$id)->Select('user_leaves.id','users.name','user_leaves.UserID','user_leaves.DateFrom','user_leaves.DateTo','user_leaves.Reason','user_leaves.ApprovalStatus','user_leaves.ApprovedUserID','user_leaves.ApprovedDate','user_leaves.ApprovalComments')->get();
         foreach($userLeaves as $userLeave){
            $userLeave->ApprovalStatus =($userLeave->ApprovalStatus == 0 ? 'Awaiting Approval' : ($userLeave->ApprovalStatus == 1 ? 'Approved' : 'Rejected'));
        }
          // $userLeave = UserLeaves::where('UserID', $id)->get();
          $count = $userLeaves->count();
            return $this->success($userLeaves,'A total of '.$count.' Leave Information(s) retrieved', 201);
        } else {
            return $this->error('User Leave Not Found', 404);
        }

    }
    catch(\Throwable $e ){
        return $this->error($e->getMessage(),400);
    }
    }
    public function getleavebyleaveid()
    {
        try{
            $id=Request('id');
            $userLeaves = UserLeaves::where('id', $id)
            ->get();
            //check leave status is 0 then sent status as Awaiting Approval and 1 as Approved
            foreach($userLeaves as $userLeave){
                $userLeave->ApprovalStatus =($userLeave->ApprovalStatus == 0 ? 'Awaiting Approval' : ($userLeave->ApprovalStatus == 1 ? 'Approved' : 'Rejected'));
            }
            if($userLeaves) {
                return $this->success($userLeaves,'User Leave retrieved successfully', 201);
            } else {
                return $this->success($userLeaves,'User Leave Not Found', 200);
            }
        }
    catch(\Throwable $e ){
        return $this->error($e->getMessage(),400);
    }
    }
    public function getleaveofalluser()
    {
        try{
            $userLeave = UserLeaves::where('ApprovalStatus',request('status'))
            ->join ('users','users.id','=','user_leaves.UserID')
            ->orderby('user_leaves.DateFrom','desc')
            ->get();
            foreach($userLeave as $userLeave){
                $userLeave->ApprovalStatus =($userLeave->ApprovalStatus == 0 ? 'Awaiting Approval' : ($userLeave->ApprovalStatus == 1 ? 'Approved' : 'Rejected'));
            }
            if($userLeave) {
                return $this->success($userLeave,'User Leave retrieved successfully', 201);
            } else {
                return $this->success($userLeave,'User Leave Not Found', 200);
            }
        }
    catch(\Throwable $e ){
        return $this->error($e->getMessage(),400);
    }
    }

}
