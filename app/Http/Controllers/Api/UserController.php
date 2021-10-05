<?php

namespace App\Http\Controllers\Api;

use App\Help\UploadImage;
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
                $user = User::where( "email" , $request->email )->first();
                return new TokenResource([
                    "status" => 200,
                    "api_token" => $user->api_token
                ] );
            }else{
                if( User::where( "email" , $request->email )->get()->count() == 0 ) {
                    return new JsonResponse(
                        [
                            "status" => 404,
                            "error" => "User Not Found"
                        ], 202
                    );
                }else{
                    return new JsonResponse(
                        [
                            "status" => 404,
                            "error" => "Password Wrong"
                        ], 202
                    );
                }
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
                'image' => 'required',
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
            // url('/') . '/storage/'. $image->fileName;
            $upload = new UploadImage($request,"user");
            $user->image = $upload->fileName;
            $user->slug_skills = $request->slug_skills;
            $user->save();
            return new TokenResource([
                "status" => 200,
                "api_token" => $user->api_token
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
     * @return UserResource
     */
    public function findProfile(Request $request )
    {
        return new UserResource($request->user() );
    }

    public function editUser( Request $request )
    {
        $id = $request->user()->id;
        $user = User::find($id);
        if( $request->has('name') ){
            $user->name = $request->get('name');
        }
        if( $request->has('phone_number') ){
            $user->phone_number = $request->get('phone_number');
        }
        if( $request->has('address') ){
            $user->address = $request->get('address');
        }
        if( $request->has('slug_skills') ){
            $user->slug_skills = $request->get('slug_skills');
        }
        if( $request->hasFile('image') ){
            $file = new UploadImage($request,"user");
            $user->image = $file->fileName;
        }
        $user->save();
        return UserResource::make($user);
    }

    public  function resetPassword( Request $request ){
        $validate = Validator::make(
            $request->all(),
            [
                "password" => "required|string",
                "new_password" => "required|string"
            ]
        );
        if( $validate->fails() ){
            return new JsonResponse($validate->errors()->all(),202);
        }else{
            $id = $request->user()->id;
            $user = User::find($id);
            $user->password = Hash::make($request->get('new_password'));
            $user->save();
            return UserResource::make ($user,200);
        }
    }
}
