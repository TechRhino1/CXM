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
        try{
            //join the two tables
            $invoices = invoices:: join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->select('invoices.*', 'invoice_details.*')
            ->get();
            //$invoices = $invoices->selectRaw('CASE WHEN invoices.status = 0 THEN "Draft" WHEN invoices.status = 1 THEN "Approved" WHEN invoices.status = 2 THEN "Send" WHEN invoices.status = 4 THEN "Received" ELSE "Unknown" END AS status');

            return $this->success($invoices, 'A total of ' . $invoices->count() . ' Invoice(s) retrieved', 200);
        }
        catch(\Throwable $e){
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
        try{
            $data = $request->all();
            $addinvoice = invoices::create([
                'year' => $data['year'],
                'month' => $data['month'],
                'date_created' => $data['date_created'],
                'user_created' => $data['user_created'],
                'invoice_date' => $data['invoice_date'],
                'client_id' => $data['client_id'],
                'currency' => $data['currency'],
                'amount' => $data['amount'],
                'status' => $data['status'],
                'amount_received' => $data['amount_received'],
                'conversion_rate' => $data['conversion_rate'],
                'date_received' => $data['date_received'],
            ]);

          //get invoice id
            $invoice_id = $addinvoice->id;

            $addinvoice = invoice_details::create([
                'invoice_id' => $invoice_id,
                'task_id' => $data['task_id'],
                'project_id' => $data['project_id'],
                'updated_comments' => $data['updated_comments'],
                'updated_time' => $data['updated_time'],
            ]);
            return $this->success($addinvoice, 'New Invoice created successfully', 201);

        }
        catch(\Throwable $e){
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
         try{
            $id = Request('id');

            $updateinvoice = invoices::where('id', $id)->update([
                'year' => $request->year,
                'month' => $request->month,
                'date_created' => $request->date_created,
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
            $updateinvoice = invoice_details::where('invoice_id', $id)->update([
                // 'invoice_id' => $request->invoice_id,
                // 'task_id' => $request->task_id,
                // 'project_id' => $request->project_id,
                'updated_comments' => $request->updated_comments,
                'updated_time' => date('Y-m-d H:i:s'),

            ]);
            return $this->success($updateinvoice, 'Invoice updated successfully', 200);
         }
            catch(\Throwable $e){
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
        try{
            $id = Request('id');
            $deleteinvoice = invoices::where('id', $id)->delete();
            $deleteinvoice = invoice_details::where('invoice_id', $id)->delete();
            return $this->success($deleteinvoice, 'Invoice deleted successfully');
        }
        catch(\Throwable $e){
            return $this->error($e->getMessage(), 500);
        }
    }
}
