<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadDetails;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class LeadController extends Controller
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
            $leads = Lead::all([
                'id',
                'companies_id',
                'name',
                'email',
                'phone',
                'date_created',
                'date_last_followup',
                'date_next_followup',
            ]);

            return $this->success($leads, 'A total of ' . $leads->count() . ' Lead(s) retrieved', 200);

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
            $addlead = Lead::create([
                'companies_id' => $request->companiesid,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_created' => $request->date_created,
                'date_last_followup' => $request->date_last_followup,
                'date_next_followup' => $request->date_next_followup,
            ]);

            $addlead->save();

            $addleadetails = LeadDetails::create([
                'leads_id' => $addlead->id,
                'date_created' => $request->date_created,
                'comments' => $request->comments,
            ]);

            $addleadetails->save();

            return $this->success($addlead, 'Lead added successfully', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        try{
            $id = Request('id');
            $lead = Lead::where('id', $id)->first();
            $lead->companies_id = $request->companiesid;
            $lead->name = $request->name;
            $lead->email = $request->email;
            $lead->phone = $request->phone;
            $lead->date_created = $request->date_created;
            $lead->date_last_followup = $request->date_last_followup;
            $lead->date_next_followup = $request->date_next_followup;
            $lead->save();

            $leadetails = LeadDetails::where('leads_id', $id)->first();
            $leadetails->date_created = $request->date_created;
            $leadetails->comments = $request->comments;
            $leadetails->save();

            return $this->success($lead, 'Lead updated successfully', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $lead = Lead::where('id', $id)->first();

            $lead->delete();

            return $this->success($lead, 'Lead deleted successfully', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }


}
