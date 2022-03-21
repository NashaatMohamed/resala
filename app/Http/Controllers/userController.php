<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    use GeneralTrait;
    public function register(Request $request) {

        $user = User::create([
            'fullName' => $request->input('fullName') ,
            'email' => $request->input('email') ,
            'phone' => $request->input('phone') ,
            'password' => bcrypt($request->input('password')),
            'address' => $request->input('address') ,

        ]);

        return $this->returnData('user',$user,'User registered successfully','201');
    }


    // to login as a user or register
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->returnError('404','You are unauthenticated');
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return $this->returnError('404','You are unauthenticated');
        }

        return $this->createNewToken($token);

    }


    // to return user token
    protected function createNewToken($token)
    {
        $id =auth()->user()->id;
        return $this->returntoken('user_id',$id,'user_token',$token,'You logged successfully','201');
    }

    public function logout()
    {
        auth()->logout();

        return $this->returnSuccessMessage('user logout successfully', '201');
    }
}
