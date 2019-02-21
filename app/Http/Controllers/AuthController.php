<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(Request $request){
        // signup a new user
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        // create a new user
        $new_user = new User(
            [
                'name'=>$name,
                'email'=>$email,
                'password'=>bcrypt($password)
            ]
        );
        if($new_user->save()){
           $response = [
               'msg'=>'User created well',
           ];
           return response()->json($response, 201); 
        }
        $response =[
            'msg'=>'An error occured'
        ];
        return response()->json($response, 404);
    }

    public function signin(Request $request){
        // sign in a user
        $email = $request->input('email');
        $password = $request->input('password');
    }

}
