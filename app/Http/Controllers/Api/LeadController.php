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
                'name' => 'required|string|max:200',
                'email' => 'required|email',
                'phone' => 'required',
                'date_last_followup' => 'required',
                'date_next_followup' => 'required',
                'comments' => 'required|string',

            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }else{

            $addlead = Lead::create([
                'companies_id' => $request->companies_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_created' => date('Y-m-d'),
                'date_last_followup' => $request->date_last_followup,
                'date_next_followup' => $request->date_next_followup,
            ]);

            $lead_details = LeadDetails::create([
                'leads_id' => $addlead->id,
                'date_created' => date('Y-m-d'),
                'comments' => $request->comments,
            ]);
        }


            return $this->success($addlead, 'Lead added successfully', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }



    public function updateLead(Request $request)
    {
        try{
            $id = Request('leads_id');
            $lead = Lead::where('id', $id)->first();
            $lead->update([
                'companies_id' => $request->companies_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_created' => date('Y-m-d'),
                'date_last_followup' => $request->date_last_followup,
                'date_next_followup' => $request->date_next_followup,

            ]);
            $lead_details = LeadDetails::create([
                'leads_id' => $request->leads_id,
                'date_created' => date('Y-m-d'),
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

            $leads = Lead::join('lead_details', 'leads.id', '=', 'lead_details.leads_id')->get();

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

            $leads = Lead::leftjoin('lead_details', 'leads.id', '=', 'lead_details.leads_id')
                ->join('companies', 'leads.companies_id','=','companies.id')
                ->whereMonth('leads.date_created', $month)->whereYear('leads.date_created', $year)
                ->select('leads.*','lead_details.*','companies.name as companies_name')
                ->orderby('lead_details.created_at', 'desc')
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
            $leads = Lead::leftjoin('lead_details', 'leads.id', '=', 'lead_details.leads_id')
                ->where('lead_details.id', $getleadid)
                ->select('leads.*', 'lead_details.*')
                ->orderby('lead_details.created_at', 'desc')
                ->limit(1)
                ->get();


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
