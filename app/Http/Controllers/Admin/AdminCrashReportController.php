<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrashReport;
use App\Traits\CommonQueryTrait;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdminCrashReportController extends Controller
{

    public function __construct(protected Request $request, protected CrashReport $crashReport) {}

    public function index()
    {
        try {
            $limit = $this->request->input('limit', 10);
            $page = $this->request->input('page', 1);
            $query = $this->crashReport->query();
            $query->dateOrderFilter($this->request->date)
                ->selfSearchFilter($this->request->search);
            $data = $query->paginate($limit, ['*'], 'page', $page);

            // return response()->json($data);

            if ($this->request->ajax()) {
                return view('admin.crash-report.table', compact('data'));
            }
            return view('admin.crash-report.index', compact('data'));
        } catch (\Throwable $th) {
            //throw $th;
            Alert::error('Something went wrong !', 'error');
            return redirect()->back();
        }
    }

    public function detail($id)
    {

        try {
            $data = $this->crashReport->find($id);
            if ($data == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            // return response()->json($data);
            return view('admin.crash-report.detail', compact('data'));
        } catch (\Throwable $th) {
            //throw $th;

            Alert::error('Something went wrong !', 'error');
            return redirect()->back();
        }
    }
}
