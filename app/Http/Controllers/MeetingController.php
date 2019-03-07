<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Meeting;
use JWTAuth;

class MeetingController extends Controller
{

    public function __construct(){
        // add middleware to protect certain actions
        // include the actions to be protected
        $this->middleware('jwt.auth', ['only'=>[
            'update', 'store', 'destroy'
        ]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // returns a list of meetings
        $meetings = Meeting::all();
        // loop through the meetings array
        foreach($meetings as $meeting){
            $meeting ->view_meeting =[
                'href'=>'api/v1/meeting/' . $meeting->id,
                'method'=>'GET'
            ];
        }

        $response = [
            'message'=>'List of meetings',
            'meeting' => $meetings
        ];
        return response()->json($response, 200);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation
        $this->validate($request,[
            'title'=> 'required',
            'description' => 'required',
            'time' => 'required|date_format:YmdHie',
        ]);
        // get user authenticated with the passed token
        if(! $user = JWTAuth::parseToken()->authenticate()){
            return response()->json(['msg'=>'user not found'], 404);
        }
        

        $title = $request->input('title');
        $description = $request->input('description');
        $time = $request->input('time');
        // get user id from the id of the user extracted from the token
        $user_id = $user->id;
        // initialize a new meeting
        $meeting = new Meeting([
                'time'=>Carbon::createFromFormat('YmdHie', $time),
                'title'=>$title,
                'description'=>$description
        ]);
        // check if meeting was created well
        if($meeting->save()){
            // add connection between meetings and user
            $meeting->users()->attach($user_id);
            $meeting->view_meeting = [
                'href'=>'api/v1/meeting/' . $meeting->id,
                'method'=>'GET'
            ];
            $message = [
                'msg'=>'Meeting created',
                'meeting'=>$meeting
            ];
            return response()->json($message, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get a single meeting
        // load the meeting with the users in that meeting
        // so get meeting data + user data

        // firstorfail allows it to return 404 if it fails
        $meeting = Meeting::with('users')->where('id', $id)->firstOrFail();
        $meeting->view_meetings = [
            'href'=>'api/v1/meeting',
            'method'=>'GET'
        ];
        $response = [
            'msg'=>'Meeting information',
            'meeting'=>$meeting
        ];
        return response()->json($response, 200);
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
        return "it works";
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
        //validation
        $this->validate($request,[
            'title'=> 'required',
            'description' => 'required',
            'time' => 'required|date_format:YmdHie',
        ]);
        
        // get user authenticated with the passed token
        if(! $user = JWTAuth::parseToken()->authenticate()){
            return response()->json(['msg'=>'user not found'], 404);
        }
        $title = $request->input('title');
        $description = $request->input('description');
        $time = $request->input('time');
        $user_id = $user->id;
        $meeting = [
            'title'=> $title,
            'description'=>$description,
            'time'=>$time,
            'user_id'=>$user_id,
            'view_meeting'=>[
                'href'=> 'api/v1/meeting/1',
                'method'=> 'GET'
            ]
        ];
        // check if the meeting exists
        $meeting = Meeting::with('users')->findOrFail($id);
        // check if the user id is registered for the meeting
        if(!$meeting->users()->where('users.id', $user_id)->first()){
            return response()->json([
                'msg'=>'user not registered for the meeting'
            ], 401);
        };
        $meeting->time = Carbon::createFromFormat('YmdHie', $time);
        $meeting->title = $title;
        $meeting->description = $description;
        // check if update fails
        if(!$meeting->update()){
            return response()->json(['msg'=>'error during updating'], 404);
        }
        $meeting->view_meeting =[
            'href'=>'api/v1/meeting' . $meeting->id,
            'method'=>'GET'
        ];
        $response = [
            'msg'=>'Meeting updated',
            'meeting'=> $meeting
        ];
        return response()->json($response, 200);

            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // get user authenticated with the passed token
        if(! $user = JWTAuth::parseToken()->authenticate()){
            return response()->json(['msg'=>'user not found'], 404);
        }
        // check if the user id is registered for the meeting
        if(!$meeting->users()->where('users.id', $user->id)->first()){
            return response()->json([
                'msg'=>'user not registered for the meeting'
            ], 401);
        };
       // check if the meeting exists
       $meeting = Meeting::findOrFail($id);
       // fetch all users attatched to that meeting
       $users = $meeting->users;
       // detatch all users
       $meeting->users()->detach();
       // if deleting fails, reattatch the users
       if(!$meeting->delete()){
           foreach($users as $user){
               $meeting->users()->attach($user);
           };
           return response()->json(['msg'=>'deletion failed'], 404);
       };
       $response = [
           'msg'=>'Message deleted'
       ];
       return response()->json($response, 200);
    }
}
