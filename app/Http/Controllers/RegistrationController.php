<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meeting;
use App\User;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'meeting_id'=> 'required',
            'user_id'=>'required'
        ]);

        // allows us to register a new meeting
        $meeting_id = $request->input('meeting_id');
        $user_id = $request->input('user_id');

        // fetch meeting and users
        $meeting = Meeting::findOrFail($meeting_id);
        $user = User::findOrFail($user_id);
        // dummy messager if user is already registered for the meeting
        $message = [
            'msg'=>'user already registered for the meeting',
            'user'=>$user,
            'meeting'=>$meeting
        ];
        // check if user with that id is registered for this meeting 
        if($meeting->users()->where('users.id', $user->id)->first()){
            return response()->json($message, 404);
        };
        // attatch user to the meeting
        $user->meetings()->attach($meeting);
        $response =[
            'msg'=>'User registered for the meeting',
            'meeting'=>$meeting,
            'user'=>$user
        ];
        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
