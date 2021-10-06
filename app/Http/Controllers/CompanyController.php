<?php

namespace App\Http\Controllers;

use App\Help\UploadImage;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function show()
    {
        $companies = Company::all();
        return CompanyResource::collection($companies);
    }
    public function index($id){
        $company = Company::find($id);
        return CompanyResource::make($company);
    }


    public function create( Request $request )
    {
        $req = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'location' => 'required',
                'description' => 'required',
                'support_phone_number' => 'required',
                'category_id' => 'required',
                'image' => 'required',
            ]
        );
        if( $req->fails() ){
            return new JsonResponse($req->errors()->all(),202);
        }else{
            $company = new Company;

            if( $request->has('web_site') ){
                $company->web_site = $request->get('web_site');
            }
            if( $request->has('email') ){
                $company->email = $request->get('email');
            }

            $company->name = $request->get('name');
            $company->location = $request->get('location');
            $company->description = $request->get('description');
            $company->support_phone_number = $request->get('support_phone_number');
            $company->category_id = $request->get('category_id');
            if( $request->hasFile('image') ){
                $file = new UploadImage($request,"companies");
                $company->image = $file->fileName;
            }
            $company->user_id = $request->user()->id;
            $company->save();

            return CompanyResource::make($company);

        }
    }
}
