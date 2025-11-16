<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Companie;
use App\Models\Financing;
use Illuminate\Http\Request;

class FinancingsController extends Controller
{
    public function add_new_financings(Request $request)
    {

        $company = Companie::where('user_id', $request->user_id)->first();
        if (!$company){
            return response('not null',404);
        }


        Financing::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'iqama_number'=>$request->iqama_number,
            'application_id'=>$request->application_id,
            'financingcompanies'=>$request->company_name,
            'price'=>$request->amount,
            'company_id'=>$company->id,
        ]);



        

    return response('suucess',200);
    }
}
