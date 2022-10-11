<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'year' => 'required',
            'month' => 'required',
           // 'date_created' => 'required',
          //  'user_created' => 'required',
            'invoice_date' => 'required',
           // 'client_id' => 'required',
            'currency' => 'required',
            'amount' => 'required',
            'status' => 'required',
            'amount_received' => 'required',
            'conversion_rate' => 'required',
            'date_received' => 'required',
        ];
    }
}
