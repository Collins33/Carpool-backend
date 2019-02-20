<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(Request $request){
        // signup a new user
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
    }

    public function signin(Request $request){
        // sign in a user
        $email = $request->input('email');
        $password = $request->input('password');
    }

}
