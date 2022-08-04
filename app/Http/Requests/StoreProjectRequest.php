<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'Description' => 'required|string|max:255',
            'Billing' => 'required|numeric',
            'BillingType' => 'required|string|max:255',
            'TotalHours' => 'required|numeric',
            'Status' => 'required|string|max:255',
            'HourlyINR' => 'required|numeric',
            'Currency' => 'required|string|max:255',
            'StartDate' => 'required',
            'EndDate' => 'required',
            'TotalClientHours' => 'required|numeric',
            'UserID' => 'required|numeric',
            // 'Comments' => '',
            // 'InternalComments' => '',
            'ClientID' => 'required|numeric',
        
        ];
    }
}
