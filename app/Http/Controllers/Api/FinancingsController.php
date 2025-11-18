<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Financing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancingsController extends Controller
{
    public function add_new_financings(Request $request)
    {

        $company = Company::where('user_id', $request->company_id)->first();

        if (! $company) {
            return response('not null', 404);
        }

        Financing::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'iqama_number' => $request->iqama_number,
            'application_id' => $request->application_id,
            'financingcompanies' => $request->financingcompanies,
            'price' => $request->price,
            'company_id' => $company->user_id,
            'financing_type_id' => $company->financing_type_id,
            'application_uuid'=>$request->application_uuid
        ]);

         return response('suucess',200);
    }



}
