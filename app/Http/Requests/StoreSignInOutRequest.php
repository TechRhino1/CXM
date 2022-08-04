<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSignInOutRequest extends FormRequest
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
            // 'user_id' => 'required',
            // 'user_id' => 'required|exists:users,id',
            'EVENTDATE' => 'required',
            'SIGNIN_TIME' => 'required',
            'CREATEDSIGNIN_DATE' => 'required',
            'CREATEDSIGNIN_TIME' => 'required',
            'SIGNOUT_TIME' => '',
            'CREATEDSIGNOUT_DATE' => '',
            'CREATEDSIGNOUT_TIME' => '',
            'TotalMins' => '',
            'TotalTaskMins' => '',
        
        ];

        //

        
        


        
      
    }
}
