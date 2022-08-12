<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoretaskRequest extends FormRequest
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
        try{
        return [
            'Title' => 'required|string|max:255',
            'Description' => 'required|string|max:255',
            'ProjectID' => 'required|integer',
            'CreaterID' => 'required|integer',
            'EstimatedDate' => 'required|date',
            'EstimatedTime' => 'required',
            'Priority' => 'required',
            'CurrentStatus' => 'required',
            // 'InitiallyAssignedToID' => 'required|integer',
            // 'CurrentlyAssignedToID' => 'required|integer',
            'CompletedDate' => 'required',
            'CompletedTime' => 'required',
            // 'ParentID' => 'required|integer',
       
        ];
        }catch(\Throwable $e){
            return $this->error($e->getMessage(), 500);
        }
    }
}
