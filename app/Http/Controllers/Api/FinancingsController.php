<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Companie;
use Illuminate\Http\Request;

class FinancingsController extends Controller
{
    public function add_new_financings(Request $request)
    {

        $company = Companie::where('user_id', $request->user_id)->first();
        if (! $company) {
            return response('not null', 404);
        }

    }
}
