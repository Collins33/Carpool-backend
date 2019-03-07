<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

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
        // validate the data
        $this->validate($request, [
            'email'=>'required|email',
            'password'=>'required'
        ]);
        // only extract the email and password
        $credentials = $request->only('email', 'password');
        // try catch block
        try{
            // try to generate the token with the passed credentials
            if(! $token=JWTAuth::attempt($credentials)){
                return response()->json([
                    'msg'=>'Invalid credentials'
                ], 401);
            }
        }catch(JWTException $e){
            return response()->json([
                'msg'=>'Could not create token'
            ], 500);
        }
        return response()->json(['token'=>$token],200);
    }
}
