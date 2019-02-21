<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Meeting;

class MeetingController extends Controller
{

    public function __construct(){

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
            'user_id'=>'required'
        ]);
        
        $title = $request->input('title');
        $description = $request->input('description');
        $time = $request->input('time');
        $user_id = $request->input('user_id');
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
        //
        $title = $request->input('title');
        $description = $request->input('description');
        $time = $request->input('time');
        $user_it = $request->input('user_id');
        return "it works";
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
        return "it works";
    }
}
