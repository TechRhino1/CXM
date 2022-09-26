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
    try{
        return [
                'name' => 'required | string | max:255',
                'email' => 'required | email',
                'phone' => 'required | numeric',
                'leads_id' => 'required | numeric',
                'address' => 'required | string',
                'comments' => 'required | string',
                'staffid' => 'required | numeric',
                'status' => 'required',
                'companies_id' => 'required | numeric',
        ];
    }
    catch(\Throwable $e){
        return $this->error($e->getMessage(), 400);
    }

    }
}
