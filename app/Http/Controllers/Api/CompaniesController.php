<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\companies;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Requests\CompanyRequest;

class CompaniesController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $companies = companies::all();
            $companies->map(function ($company) {
                $company->logo = url("public/images/$company->logo");
                return $company;
            });
            return $this->success($companies, 'A total of ' . $companies->count() . ' Company(s) retrieved', 200);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(CompanyRequest $request)
    {
        try {
            $logo = $request->file('logo');
            //  if (isset($logo)){
            if ($this->checkImageExtension($logo->extension())) {
                $logo_name = time() . rand() . '.' . $logo->extension();
                $logo->move(public_path('/images'), $logo_name);
            } else {
                return $this->error('Invalid file type', 400);
            }
            // }else{
            //     $logo_name = "No Image";
            // }
            $company = companies::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'logo' => $logo_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_type' => $request->bank_account_type,
                'bank_account_branch' => $request->bank_account_branch,
                'bank_address' => $request->bank_address,
                'bank_account_ifsc' => $request->bank_account_ifsc,
                'bank_account_swiftcode' => $request->bank_account_swiftcode,
                'invoice_header' => $request->invoice_header,
                'invoice_company_details' => $request->invoice_company_details,
                'invoice_sub_header_left' => $request->invoice_sub_header_left,
                'invoice_sub_header_right' => $request->invoice_sub_header_right,
                'invoice_footer' => $request->invoice_footer,
                'template_content' => $request->template_content,
            ]);

            return $this->success($company, 'Company created successfully', 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function show(companies $companies)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function edit(companies $companies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, companies $companies)
    {
        try {
            $ID = request('id');
            $logo = '';
            if (isset($request->logo)) {
                $logo = $request->file('logo');
                if ($this->checkImageExtension($logo->extension())) {
                    $logo_name = time() . rand() . '.' . $logo->extension();
                    $logo->move(public_path('/images'), $logo_name);

                } else {
                    throw new \Exception('Invalid file type');
                }
            } else {
                $logo_name = $request->logoUrl;
            }
            $company = companies::where('id', $ID)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'logo' => $logo_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_type' => $request->bank_account_type,
                'bank_account_branch' => $request->bank_account_branch,
                'bank_address' => $request->bank_address,
                'bank_account_ifsc' => $request->bank_account_ifsc,
                'bank_account_swiftcode' => $request->bank_account_swiftcode,
                'invoice_header' => $request->invoice_header,
                'invoice_company_details' => $request->invoice_company_details,
                'invoice_sub_header_left' => $request->invoice_sub_header_left,
                'invoice_sub_header_right' => $request->invoice_sub_header_right,
                'invoice_footer' => $request->invoice_footer,
                'template_content' => $request->template_content,
            ]);
            return $this->success($company, 'Company updated successfully', 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function destroy(companies $companies)
    {
        try {
            $ID = request('id');
            $company = companies::where('id', $ID)->delete();
            return $this->success($company, 'Company deleted successfully', 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
    public function getCompanyDetails(Request $request)
    {
        try {
            $month = $request->month;
            $year = $request->year;
            $company = companies::whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            $company->map(function ($company) {
                $company->logo = url("public/images/$company->logo");
                return $company;
            });
            return $this->success($company, 'Company details retrieved successfully', 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function getCompanyById(Request $request)
    {
        try {
            $id = $request->id;
            $company = companies::where('id', $id)->get();
            $company->map(function ($company) {
                $company->logo = url("public/images/$company->logo");
                return $company;
            });
            return $this->success($company, 'Company details retrieved successfully', 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    private function checkImageExtension(String $extension)
    {

        if (
            $extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'svg'
        ) {
            return true;
        }
        return false;
    }

}
