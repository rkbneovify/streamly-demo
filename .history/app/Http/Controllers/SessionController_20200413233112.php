<?php

namespace App\Http\Controllers;
use JWTAuth;
use App\SessionTable;
use Illuminate\Http\Request;

class SessionController extends Controller
{   

     /**
     * @var
     */
    protected $user;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sessionTab = $this->user->sessions()->get(['id', 'users_id'])->toArray();

        return $sessionTab
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
            'access_token' => 'required',
            'mac_address' => 'required',
        ]);
        $date = date('Y-m-d H:i:s');
        $task = new SessionTable();
        //$task->users_id = $request->users_id;
        $task->users_id = 1;
        $task->access_token = $request->access_token;//enter dummy data for now
        $task->user_ip = $request->user_ip;
        $task->mac_address = $request->mac_address;
        $task->connected_devices = 1;
       $task->logintime = $date;;
        //$task->description = $request->description;
        $task->created_on = $date;
        $task->modified_on = $date;

        if ($this->user->tasks()->save($task))
            return response()->json([
                'success' => true,
                'task' => $task
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task could not be added.'
            ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = $this->user->sessions()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, session with id ' . $id . ' cannot be found.'
            ], 400);
        }
    
        return $task;
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
