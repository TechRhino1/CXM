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
            'Name' => 'required|string|max:255',
            'Email' => 'required|string|email',
            'Phone' => 'required|string|max:255',
            'Comments' => 'required|string|max:255',
            'StaffID' => 'required|integer',
            'Status' => 'required|integer|max:255',
        ];

    }
}
