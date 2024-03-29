<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\invoices;
use App\Models\invoice_details;
use App\Models\Tasks;
use App\Models\Projects;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Requests\StoreInvoiceRequest;
use Illuminate\Support\Facades\DB;

class InvoicesController extends Controller
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
            $invoices = invoices::join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->join('clients', 'invoices.client_id', '=', 'clients.ID')
                ->join('tasks', 'invoice_details.task_id', '=', 'tasks.ID')
                ->join('users', 'invoices.user_created', '=', 'users.ID')
                ->join('projects', 'invoice_details.project_id', '=', 'projects.ID')
                ->select('invoices.*', 'invoice_details.*', 'clients.Name as clientname', 'tasks.Title as taskname', 'users.Name as username', 'projects.Description as projectname')
                ->get();



            return $this->success($invoices, 'A total of ' . $invoices->count() . ' Invoice(s) retrieved', 200);
        } catch (\Throwable $e) {
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
    public function store(StoreInvoiceRequest $request)
    {
        try {

            $projectid = Request('project_id');
            $project = Projects::where('ID', $projectid)->first();

            $data = $request->all();
            $addinvoice = invoices::create([
                'year' => $data['year'],
                'month' => $data['month'],
                'date_created' => date('Y-m-d'),
                'user_created' => auth()->user()->ID,
                //'invoice_date' => date('Y-m-d'),
                // 'client_id' => $data['client_id'],
                'invoice_date' => $data['invoice_date'],
                'client_id' => $project->ClientID,
                'currency' => $data['currency'],
                'amount' => $data['amount'],
                'status' => $data['status'],
                'amount_received' => $data['amount_received'],
                'conversion_rate' => $data['conversion_rate'],
                'date_received' => $data['date_received'],
            ]);
            $invoice_id = $addinvoice->id;

            $data1 = $data['task'];

            foreach ($data1 as $data2) {
                $addinvoice = invoice_details::create([

                    'invoice_id' => $invoice_id,
                    'task_id' => $data2['task_id'],
                    'project_id' => $data['project_id'],
                    'updated_comments' => $data2['updated_comments'],
                    'updated_time' => $data2['updated_time'],
                ]);
            }

            //     return $this->success($addinvoice, 'Invoice created successfully', 200);
            // }
            // catch(\Throwable $e){
            //     return $this->error($e->getMessage(), 400);
            // }
            //   foreach($data as $value){
            //     $addinvoice = invoice_details::create([
            //         'invoice_id' => $invoice_id,
            //         'task_id' => $value['task_id'],
            //         'project_id' => $value['project_id'],
            //         'updated_comments' => $value['updated_comments'],
            //        // 'updated_time' => $value['updated_time'],
            //     ]);
            //     }

            // $addinvoice = invoice_details::create([
            //     'invoice_id' => $invoice_id,
            //     'task_id' => $data['task_id'],
            //     'project_id' => $data['project_id'],
            //     'updated_comments' => $data['updated_comments'],
            //     'updated_time' => $data['updated_time'],
            // ]);
            return $this->success($addinvoice, 'New Invoice created successfully', 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show(invoices $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit(invoices $invoices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(StoreInvoiceRequest $request, invoices $invoices)
    {
        try {
            $id =$request->id;
    //  dd($id);
            $updateinvoice = invoices::where('id', $id)->update([
                'year' => $request->year,
                'month' => $request->month,
                'date_created' => date('Y-m-d'),
                'user_created' => $request->user_created,
                'invoice_date' => $request->invoice_date,
                'client_id' => $request->client_id,
                'currency' => $request->currency,
                'amount' => $request->amount,
                'status' => $request->status,
                'amount_received' => $request->amount_received,
                'conversion_rate' => $request->conversion_rate,
                'date_received' => $request->date_received,
            ]);

            $data = $request->all();
            $data1 = $data['task'];
            foreach ($data1 as $data2) {
                $updateinvoice = invoice_details::where('invoice_id', $id)
               ->where('task_id', $data2['task_id'])
                ->update([
                    //'invoice_id' => $request->invoice_id,
                    //'task_id' => $data2['task_id'],
                    //'project_id' => $data['project_id'],
                    'updated_comments' => $data2['updated_comments'],
                    'updated_time' => $data2['updated_time'],
                ]);
            }

            // $updateinvoice = invoice_details::where('invoice_id', $id)->update([
            //     'invoice_id' => $request->invoice_id,
            //     'task_id' => $request->task_id,
            //     'project_id' => $request->project_id,
            //     'updated_comments' => $request->updated_comments,
            //     'updated_time' => date('H:i:s'),

            // ]);
            return $this->success($updateinvoice, 'Invoice updated successfully', 200);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(invoices $invoices)
    {
        try {
            $id = Request('id');
            $deleteinvoice = invoices::join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->where('invoices.id', $id)
                ->delete();
            return $this->success($deleteinvoice, 'Invoice deleted successfully');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function getInvoiceById(Request $request)
    {
        try {
            $id = Request('id');
            //
            $invoices = invoices::rightjoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftjoin('clients', 'invoices.client_id', '=', 'clients.ID')
                ->join('tasks', 'invoice_details.task_id', '=', 'tasks.ID')
                ->join('users', 'invoices.user_created', '=', 'users.ID')
                ->join('projects', 'invoice_details.project_id', '=', 'projects.ID')
                ->where('invoices.id', $id)
                ->select('invoices.*', 'invoice_details.*', 'clients.Name as clientname', 'tasks.Title as taskname', 'users.Name as username', 'projects.Description as projectname')
                ->orderBy('invoice_details.id', 'desc')
               // ->limit(1)
                ->get();
            return $this->success($invoices, 'Invoice retrieved successfully', 200);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function getInvoiceByMonthYear(Request $request)
    {
        try {
            $month = Request('month');
            $year = Request('year');
            DB::statement("SET SQL_MODE=''");
            $invoices = invoices::rightjoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->whereMonth('invoice_date', $month)->whereYear('invoice_date', $year)
                ->leftjoin('clients', 'invoices.client_id', '=', 'clients.ID')
                ->join('tasks', 'invoice_details.task_id', '=', 'tasks.ID')
                ->join('users', 'invoices.user_created', '=', 'users.ID')
                ->join('projects', 'invoice_details.project_id', '=', 'projects.ID')
                ->select('invoices.*', 'invoice_details.updated_comments', 'clients.Name as clientname', 'tasks.Title as taskname', 'users.Name as username', 'projects.Description as projectname')
                ->groupBy('invoices.id')
                ->get();

            return $this->success($invoices, 'Invoice retrieved successfully', 200);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    //generate invoice pdf for invoice id
    // public function generateInvoicePdf(Request $request){
    //     try{
    //         $id = Request('id');
    //         $invoices = invoices::join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    //         ->where('invoices.id', $id)
    //         ->select('invoices.*', 'invoice_details.*')
    //         ->get();
    //         $invoices->map(function($invoice){
    //             if($invoice->status == 0){
    //                 $invoice->status = 'Draft';
    //             }elseif($invoice->status == 1){
    //                 $invoice->status = 'Approved';
    //             }elseif($invoice->status == 2){
    //                 $invoice->status = 'Send';
    //             }elseif($invoice->status == 4){
    //                 $invoice->status = 'Received';
    //             }elseif($invoice->status == 5){
    //                 $invoice->status = 'Unknown';
    //             }
    //             return $invoice;
    //         });
    //         $pdf = PDF::loadView('invoicepdf', compact('invoices'));
    //         return $pdf->download('invoice.pdf');
    //     }
    //     catch(\Throwable $e){
    //         return $this->error($e->getMessage(), 500);
    //     }
    // }



}
