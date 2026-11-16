<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BugReporting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdminBugReportController extends Controller
{
    public function __construct(
        public Request $request,
        public BugReporting $bugReporting
    ) {
    }

    public function index()
    {
        $limit = $this->request->limit;
        $page = $this->request->page;

        try {
            $query = $this->bugReporting->query();
            $query->search($this->request->search)->filterDateOrder($this->request->date)->latest();
            $data = $query->paginate($limit, ['*'], 'page', $page);

            // return response()->json($data);

            if($this->request->ajax()){
                return view('admin.bug-report.table',compact('data'));
            }
            return view('admin.bug-report.index',compact('data'));

        } catch (\Throwable $th) {
            //throw $th;
            Alert::error($th->getMessage());
            return response()->back();
        }
    }

    public function show($id){
     try {
         $query = $this->bugReporting->query();
          $data =  $query->where('id', $id)->with('files')->first();
            //  return response()->json($data);
          return view('admin.bug-report.show',compact('data'));
     } catch (\Throwable $th) {
        //throw $th;
         Alert::error($th->getMessage());
        return response()->back();
     }
    }
}