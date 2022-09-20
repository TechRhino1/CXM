<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreclientsRequest extends FormRequest
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
            'Companies_id' => 'required',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'leads_id' => 'required|string',
            'Comments' => 'required|string',
            'StaffID' => 'required|string',
            'Status' => 'required|string',
        ];

    }
}
