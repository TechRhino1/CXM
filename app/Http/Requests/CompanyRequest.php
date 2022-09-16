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
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'logo' => 'required|image | mimes:jpeg,png,jpg,gif,svg | max:2048',
            'bank_account_name' => 'required|string',
            'bank_account_number' => 'required|string',
            'bank_account_type' => 'required|string',
            'bank_account_branch' => 'required|string',
            'bank_address' => 'required|string',
            'bank_account_ifsc' => 'required|string',
            'bank_account_swiftcode' => 'required|string',
            'invoice_header' =>     'required|string',
            'invoice_company_details' => 'required|string',
            'invoice_sub_header_left' => 'required|string',
            'invoice_sub_header_right' => 'required|string',
            'invoice_footer' => 'required|string',
            'template_content' => 'required',
        ];

    }
}
