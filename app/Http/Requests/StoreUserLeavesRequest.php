<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserLeavesRequest extends FormRequest
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
           
            'UserID' => 'required',
            'DateFrom' => 'required',
            'DateTo' => 'required',
            'Reason' => 'required',
            'ApprovalStatus' => 'required',
            'ApprovedUserID' => 'required',
            'ApprovedDate' => 'required',
            'ApprovalComments' => 'required',
        
        ];
    }
}
