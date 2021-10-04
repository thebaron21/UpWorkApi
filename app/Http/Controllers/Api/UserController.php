<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TokenResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use  App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return TokenResource|array|JsonResponse
     */
    public function login(Request $request )
    {
        $validate = Validator::make(
            $request->all(),
            [
                "email" => 'required|email',
                "password" => 'required|string'
            ]
        );
        if( $validate->fails() ){
            return [
                "status" => 202,
                $validate->errors()->all()
            ];
        }else{
            $credentials = $request->only( 'email' , 'password' );
            if( Auth::attempt($credentials) ){
                $password = Hash::make( $request->password );
                $user = User::where( "email" , $request->email )->first();
                return new TokenResource([
                    "status" => 200,
                    "api_token" => $request->user()->api_token
                ] );
            }else{
                return new JsonResponse(
                    [
                        "status" => 404,
                        "error" => "User Not Found"
                    ],202
                );
            }
        }
    }

    /**
     * @param Request $request
     * @return TokenResource|array
     */
    public function register(Request $request )
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string',
                'phone_number' => 'required|string',
                'address' => 'required|string',
                'image' => 'required|string',
                'slug_skills' => 'required|string'
            ]
        );
        if( $validate->fails() ){
            return [
                'status' => 202,
                $validate->errors()->all()
            ];
        }else{
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone_number = $request->phone_number;
            $user->address = $request->address;
            $user->image = $request->image;
            $user->slug_skills = $request->slug_skills;
            $user->save();
            return new TokenResource([
                "status" => 200,
                "api_token" => $request->user()->api_token
            ] );

        }
    }

    public function findUser( $id )
    {
        $user = User::find($id);
        return new UserResource($user);
    }


    /**
     * @param Request $request
     * @return TokenResource
     */
    public function findProfile(Request $request )
    {
        return new TokenResource([
            "status" => 200,
            "api_token" => $request->user()->api_token
        ] );
    }

    public function editUser( Request $request )
    {

    }
}
