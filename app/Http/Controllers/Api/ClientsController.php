<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class ClientsController extends Controller
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

            $clients = Clients::all([
                'id',
                'companies_id',
                'name',
                'email',
                'phone',
                'leads_id',
                'comments',
                'staffID',
                'status',
            ]);

            return $this->success($clients, 'A total of ' . $clients->count() . ' Client(s) retrieved', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
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
    public function store(Request $request)
    {
        try {
            $addclient = Clients::create([
                'Companies_id' => $request->Companies_id,
                'Name' => $request->name,
                'Email' => $request->email,
                'Phone' => $request->phone,
                'leads_id' => $request->leads_id,
                'Comments' => $request->comments,
                'StaffID' => $request->staffID,
                'Status' => $request->status,
            ]);
            return $this->success($addclient, 'New Client created successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function show(Clients $clients)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function edit(Clients $clients)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $clientid = $id;
            $updateclient = Clients::where('ID', $clientid)->update([
                'Companies_id' => $request->Companies_id,
                'Name' => $request->name,
                'Email' => $request->email,
                'Phone' => $request->phone,
                'leads_id' => $request->leads_id,
                'Comments' => $request->comments,
                'StaffID' => $request->staffID,
                'Status' => $request->status,
            ]);
            return $this->success($updateclient, 'Client updated successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clients $clients)
    {
        try {
            $id = Request('id');
            $deleteclient = Clients::where('ID', $id)->delete();
            return $this->success($deleteclient, 'Client deleted successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    //get client filter by status of client 0 or 1
    public function getclientbystatus(Clients $clients)
    {
        try {

            $status = Request('status');
            $client = Clients::where('Status', $status)->get([
                'id',
                'companies_id',
                'name',
                'email',
                'phone',
                'leads_id',
                'comments',
                'staffID',
                'status',
            ]);
            return $this->success($client, 'A total of ' . $client->count() . ' Client(s) retrieved', 200);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    //get client by id
    public function getclientbyid(Clients $clients)
    {
        try {

            $id = Request('id');
            $client = Clients::where('ID', $id)->get([
                'id',
                'companies_id',
                'name',
                'email',
                'phone',
                'leads_id',
                'comments',
                'staffID',
                'status',
            ]);
            return $this->success($client, 'A total of ' . $client->count() . ' Client(s) retrieved', 200);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

}
