<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActivityEventType;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Traits\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AdminAnnouncementController extends Controller
{
    use ActivityLogger;
    public function __construct(
        public Request $request,
        public Announcement $announcement

    ) {}
    public function index()
    {

        $limit = $this->request->input('limit', 10);
        $page  = $this->request->input('page', 1);
        $query = Announcement::query();
        $query->orderByDesc('status')
            ->statusFilter($this->request->status)
            ->dateOrderFilter($this->request->date)
            ->searchFilter($this->request->search);
        $data =  $query->paginate($limit, ['*'], 'page', $page);

        if ($this->request->ajax()) {
            return view('admin.announcement.table', compact('data'));
        }
        return view('admin.announcement.index', compact('data'));
    }

    public function create()
    {
        return view('admin.announcement.create');
    }

    public function store()
    {


        $this->request->validate([
            'content' => 'required|string',
            'url' => 'required|string'
        ]);

        try {
            $this->announcement->user_id = Auth::id();
            $this->announcement->content = $this->request->input('content');
            $this->announcement->url = $this->request->url;
            $this->announcement->status = 0;
            $this->announcement->save();


            $this->activity()->event("Announcement")
                ->eventType(ActivityEventType::CREATED)
                ->description('New announcement created')
                ->isAdmin()
                ->properties([
                    "data" => $this->announcement
                ])->save();

            DB::commit();
            toast('Data saved successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Something went wrong !', 'error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $data  =  $this->announcement->where('id', $id)->first();
            if ($data == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            return view('admin.announcement.edit', compact('data'));
        } catch (\Throwable $th) {
            //throw $th;
            Alert::error('Something went wrong !', 'error');
            return redirect()->back();
        }
    }

    public function update($id)
    {
        $this->request->validate([

            'content' => 'required|string',
            'url' => 'required|string'
        ]);

        try {
            $announcement  =  $this->announcement->where('id', $id)->first();
            if ($announcement == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $this->activity()->event("Announcement")
                ->eventType(ActivityEventType::UPDATED)
                ->description('Announcement updated')
                ->isAdmin()
                ->oldData($announcement);
            $announcement->content = $this->request->input('content');
            $announcement->url = $this->request->url;
            $announcement->save();

            $this->newData($announcement->refresh());

            DB::commit();
            toast('Data updated successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Something went wrong !', 'error');
            return redirect()->back();
        }
    }

    public function status($id)
    {

        try {
            $anno  =  $this->announcement->where('id', $id)->first();
            if ($anno == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $this->announcement->where('status', 1)->update([
                'status' => 0
            ]);
            $anno->status =   $anno->status == 1 ? 0 : 1;
            $anno->save();

            $this->activity()->event("Announcement")
                ->eventType($anno->status == 1 ? ActivityEventType::STATUSDISABLE : ActivityEventType::STATUSENABLE)
                ->description('Announcement status changed')
                ->isAdmin()
                ->properties([
                    "data" => $anno->refresh()
                ])->save();

            DB::commit();
            toast('Status change successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Something went wrong !', 'error');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {

        try {
            $announcement  =  $this->announcement->where('id', $id)->first();
            if ($announcement == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }


            $this->activity()->event("Announcement")
                ->eventType(ActivityEventType::DELETED)
                ->description('Announcement deleted')
                ->isAdmin()
                ->properties([
                    "data" => $announcement->refresh()
                ])->save();

            $announcement->delete();
            DB::commit();
            toast('Data delete successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Something went wrong !', 'error');
            return redirect()->back();
        }
    }
}
