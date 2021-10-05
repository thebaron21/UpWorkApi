<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Company;
class CompanyController extends Controller
{
    public function show()
    {
        $companies = Company::all();
        return new JsonResponse(
            $companies,
            200
        );
    }
    public function index($id){
        $company = Company::find($id);
        return new JsonResponse(
            $company,
            200
        );
    }
}
