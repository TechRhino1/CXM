<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
           // 'logoUrl' => 'required|string',
           // 'logo' => 'mimes:jpeg,png,jpg,gif,svg',
           // 'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048 | nullable',
            'bank_account_name' => 'required|string',
            'bank_account_number' => 'required|numeric',
            'bank_account_type' => 'required|string',
            'bank_account_branch' => 'required|string',
            'bank_address' => 'required',
            'bank_account_ifsc' => 'required|string',
            'bank_account_swiftcode' => 'required',
            'invoice_header' =>     'required',
            'invoice_company_details' => 'required',
            'invoice_sub_header_left' => 'required',
            'invoice_sub_header_right' => 'required',
            'invoice_footer' => 'required',
            'template_content' => 'required',
        ];
    }
    catch(\Throwable $e){
        return $this->error($e->getMessage(), 400);
    }
    }
}
