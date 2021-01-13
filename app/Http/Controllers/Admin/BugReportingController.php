<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BugReporting;
use App\Http\Requests\BugReporting\Index;
use App\Http\Requests\BugReporting\Show;
use App\Http\Requests\BugReporting\Create;
use App\Http\Requests\BugReporting\Store;
use App\Http\Requests\BugReporting\Edit;
use App\Http\Requests\BugReporting\Update;
use App\Http\Requests\BugReporting\Destroy;


/**
 * Description of BugReportingController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */

class BugReportingController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        return view('admin.bug_reporting.index', ['records' => BugReporting::paginate(10)]);
    }    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  BugReporting  $bugreporting
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, BugReporting $bugreporting)
    {
        return view('admin.bug_reporting.show', [
                'record' =>$bugreporting,
        ]);

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Create $request)
    {

        return view('admin.bug_reporting.create', [
            'model' => new BugReporting,

        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $model=new BugReporting;
        $model->fill($request->all());

        if ($model->save()) {
            
            session()->flash('app_message', 'BugReporting saved successfully');
            return redirect()->route('bug_reporting.index');
            } else {
                session()->flash('app_message', 'Something is wrong while saving BugReporting');
            }
        return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit  $request
     * @param  BugReporting  $bugreporting
     * @return \Illuminate\Http\Response
     */
    public function edit(Edit $request, BugReporting $bugreporting)
    {

        return view('admin.bug_reporting.edit', [
            'model' => $bugreporting,

            ]);
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  BugReporting  $bugreporting
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request,BugReporting $bugreporting)
    {
        $bugreporting->fill($request->all());

        if ($bugreporting->save()) {
            
            session()->flash('app_message', 'BugReporting successfully updated');
            return redirect()->route('bug_reporting.index');
            } else {
                session()->flash('app_error', 'Something is wrong while updating BugReporting');
            }
        return redirect()->back();
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy  $request
     * @param  BugReporting  $bugreporting
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Destroy $request, BugReporting $bugreporting)
    {
        if ($bugreporting->delete()) {
                session()->flash('app_message', 'BugReporting successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting BugReporting');
            }

        return redirect()->back();
    }
}
