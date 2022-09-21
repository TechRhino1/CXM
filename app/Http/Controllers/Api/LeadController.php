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
    public function addLead(Request $request)
    {
        try {

                $validator = validator()->make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'date_created' => 'required',
                'date_last_followup' => 'required',
                'date_next_followup' => 'required',
                
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }else{

            $addlead = Lead::create([
                'companies_id' => $request->companies_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_created' => $request->date_created,
                'date_last_followup' => $request->date_last_followup,
                'date_next_followup' => $request->date_next_followup,
            ]);
        }



            $addlead = LeadDetails::create([
                'leads_id' => $addlead->id,
                'date_created' => date('Y-m-d'),
                'comments' => $request->comments,
            ]);



            return $this->success($addlead, 'Lead added successfully', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    

    public function updateLead(Request $request)
    {
        try{
            $id = Request('id');
            $lead = Lead::where('id', $id)->first();
            $lead->update([
                'companies_id' => $request->companies_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_created' => $request->date_created,
                'date_last_followup' => $request->date_last_followup,
                'date_next_followup' => $request->date_next_followup,
                
            ]);
            $leadetails = LeadDetails::where('leads_id', $id)->first();
            $leadetails->update([
                'date_created' => $request->date_created,
                'comments' => $request->comments,
                
            ]);

            return $this->success($lead, 'Lead updated successfully', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }


    public function getAllLeads()
    {
        try {

            $leads = Lead::leftjoin('lead_details', 'lead.id', '=', 'lead_details.leads_id')
                ->get();

            if ($leads->count() > 0) {
                return $this->success($leads, 'A total of ' . $leads->count() . ' Lead(s) retrieved', 200);
            } else {
                return $this->success($leads, 'No detail(s) found');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function getLeads()
    {
        try {

            $month = request('month');
            $year = request('year');

            $leads = Lead::leftjoin('lead_details', 'lead.id', '=', 'lead_details.leads_id')
                ->whereMonth('lead.date_created', $month)->whereYear('lead.date_created', $year)
                ->orderby('lead.date_created', 'desc')
                ->get();

            if ($leads->count() > 0) {
                return $this->success($leads, 'A total of ' . $leads->count() . ' Lead(s) retrieved', 200);
            } else {
                return $this->success($leads, 'No detail(s) found');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function getLeadById()
    {
        try {
            $getleadid = request('id');
            $leads = Lead::leftjoin('lead_details', 'lead.id', '=', 'lead_details.leads_id')
                ->where('lead_details.leads_id', $getleadid)
                ->select('lead.*', 'lead_details.*')
                ->get();

            //print_r($projects);

            if ($leads->count() > 0) {
                return $this->success($leads, ' detail(s) retrieved successfully');
            } else {
                return $this->success($leads, 'No details found');
            }
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }




}
