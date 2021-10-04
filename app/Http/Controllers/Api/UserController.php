<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * @return UserResource|array
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
                return UserResource::make( ["api_token" => $user->api_token ] );
            }else{
                return UserResource::make(
                    [
                        "status" => 404,
                        "error" => "User Not Found"
                    ]
                );
            }
        }
    }

    /**
     * @param Request $request
     * @return UserResource|array
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
            return UserResource::make( $user );

        }
    }

    public function findUser( $id )
    {

    }


    /**
     * @param Request $request
     * @return UserResource
     */
    public function findProfile(Request $request )
    {
        return new UserResource( $request->user() );
    }

    public function editUser( Request $request )
    {

    }
}
