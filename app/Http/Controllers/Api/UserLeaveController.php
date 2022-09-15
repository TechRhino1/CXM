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
        try {

            $userLeaves = UserLeaves::where('DateFrom', '>=', date('Y-m-d'))
                ->orderby('DateFrom', 'desc')
                ->get();
            foreach ($userLeaves as $userLeave) {
                $userLeave->ApprovalStatus = ($userLeave->ApprovalStatus == 0 ? 'Awaiting Approval' : ($userLeave->ApprovalStatus == 1 ? 'Approved' : 'Rejected'));
            }
            $count = $userLeaves->count();
            return $this->success($userLeaves, 'A total of ' . $count . ' Leave Information(s) retrieved successfully');
        } catch (\Throwable $e) {
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
        try {
            $UserID = auth()->user()->ID;

            $userLeave = UserLeaves::create(
                [
                    'UserID' => $UserID,
                    'DateFrom' => $request->DateFrom,
                    'DateTo' => $request->DateTo,
                    'Reason' => $request->Reason,
                    'ApprovalStatus' => $request->ApprovalStatus,
                ]
            );
            if ($userLeave) {
                return $this->success($userLeave, 'User Leave Created Successfully', 201);
            } else {
                return  $this->error('User Leave Not Created', 500);
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
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
    public function update(StoreUserLeavesRequest $request, UserLeaves $userLeaves, $id)
    {
        try {
            $userId = Request('UserID');
            $userLeave = UserLeaves::where('ID', $id)->where('UserID', $userId);
            //if ($userId ==  $userLeave->UserID) {
                if ($userLeave->first())
                 {
                  // $userLeave->update($request->all());
                  //dd($request->all());
                    $userLeave->update(
                        [
                            'DateFrom' => $request->DateFrom,
                            'DateTo' => $request->DateTo,
                            'Reason' => $request->Reason,
                            'ApprovalStatus' => $request->ApprovalStatus,
                            'ApprovedUserID' => $request->ApprovedUserID,
                            'ApprovedDate' => $request->ApprovedDate,
                            'ApprovalComments' => $request->ApprovalComments,

                        ]
                    );
                    return $this->success($userLeave, 'User Leave Updated Successfully', 200);
                } else {
                    return  $this->error('User Leave Not Found', 404);
                }
            // } else {
            //     return $this->error('You are not authorized to update this leave', 500);
            // }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
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
        try {
            $userLeave = UserLeaves::find($id);
            if ($userLeave->delete()) {
                return $this->success($userLeave, 'User Leave Deleted Successfully', 201);
            } else {
                return $this->error('User Leave Not Deleted', 500);
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function userleaveprofile($id)
    {
        try {
            if ($id !== null) {

                //print_r($id);

                $userLeaves = UserLeaves::join('users', 'users.ID', '=', 'userleaves.UserID')
                ->where('userleaves.UserID', $id)
                ->Select('userleaves.ID', 'users.Name', 'userleaves.UserID', 'userleaves.DateFrom', 'userleaves.DateTo', 'userleaves.Reason', 'userleaves.ApprovalStatus', 'userleaves.ApprovedUserID', 'userleaves.ApprovedDate', 'userleaves.ApprovalComments')
                ->get();

                foreach ($userLeaves as $userLeave) {
                    $userLeave->ApprovalStatus = ($userLeave->ApprovalStatus == 0 ? 'Awaiting Approval' : ($userLeave->ApprovalStatus == 1 ? 'Approved' : 'Rejected'));
                }
                // $userLeave = UserLeaves::where('UserID', $id)->get();
                $count = $userLeaves->count();
                return $this->success($userLeaves, 'A total of ' . $count . ' Leave Information(s) retrieved', 201);
            } else {
                return $this->error('User Leave Not Found', 404);
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function getleavebyleaveid()
    {
        try {
            $id = Request('ID');
            $userLeaves = UserLeaves::where('ID', $id)
                ->get();
            //check leave status
            foreach ($userLeaves as $userLeave) {
                $userLeave->ApprovalStatus = ($userLeave->ApprovalStatus == 0 ? 'Awaiting Approval' : ($userLeave->ApprovalStatus == 1 ? 'Approved' : 'Rejected'));
            }
            if ($userLeaves) {
                return $this->success($userLeaves, 'User Leave retrieved successfully', 201);
            } else {
                return $this->success($userLeaves, 'User Leave Not Found', 200);
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function getleaveofalluser()
    {
        try {
            $userLeaves = UserLeaves::where('ApprovalStatus', request('status'))
                ->Select('userleaves.*', 'users.name')
                ->join('users', 'users.ID', '=', 'userleaves.UserID')
                ->orderby('userleaves.DateFrom', 'desc')
                ->get();
            foreach ($userLeaves as $userLeave) {
                $userLeave->ApprovalStatus = ($userLeave->ApprovalStatus == 0 ? 'Awaiting Approval' : ($userLeave->ApprovalStatus == 1 ? 'Approved' : 'Rejected'));
            }
            if ($userLeaves->count() > 0) {
                return $this->success($userLeaves, 'A total of ' . $userLeaves->count() . ' Leave Information(s) retrieved', 201);
            } else {
                return $this->success($userLeaves, 'No Leave Information Found', 200);
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
