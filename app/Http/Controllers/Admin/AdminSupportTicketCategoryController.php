<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AdminSupportTicketCategoryController extends Controller
{
    public function __construct(
        protected Request $request,
        protected SupportTicketCategory $supportTicketCategory
    ) {}

    public function index()
    {
        $limit = $this->request->limit;
        $page = $this->request->page;

        $query = $this->supportTicketCategory->query();
        $query->statusFilter($this->request->status)
            ->searchFilter($this->request->search, 'title')
            ->dateOrderFilter($this->request->date)->latest();
        $data = $query->paginate($limit, ['*'], 'page', $page);

        if ($this->request->ajax()) {
            return view('admin.support-ticket.category.table', compact('data'));
        }

        return view('admin.support-ticket.category.index', compact('data'));
    }

    public function store()
    {

        $validator = validator($this->request->all(), [

            'title' => 'required|string|unique:support_ticket_categories,title',

        ], [
            'title.unique' => 'This category already exists. Please use a different category.',

        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $category = $this->supportTicketCategory;
            $category->title = $this->request->title;
            $category->status = 1;
            $category->save();


            DB::commit();
            toast('Data saved successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error('error', $th->getMessage());
            return redirect()->back();
        }
    }

    public function update($id)
    {
        $validator = validator($this->request->all(), [

            'title' => 'required|string|max:225|unique:blog_categories,title,' . $id,

        ], [
            'title.unique' => 'This category already exists. Please use a different category.',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $category = $this->supportTicketCategory->find($id);
            if ($category == null) {
                toast('Data not found !', 'erorr')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $category->title = $this->request->title;
            $category->save();
            toast('Data updated successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);

            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error('error', $th->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        DB::beginTransaction();
        try {
            $category = $this->supportTicketCategory->find($id);
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $message = $category->status == 1 ? "inactivated" : "activated";

            $category->status =  $category->status == 1 ? 0 : 1;
            $category->save();
            toast('Status ' . $message . ' successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);

            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error('error', $th->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $category = $this->supportTicketCategory->find($id);
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }


            $category->delete();
            toast('Data deleted successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);

            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error('error', $th->getMessage());
            return redirect()->back();
        }
    }


    public function bulkAction()
    {

        DB::beginTransaction();
        try {
            $category = $this->supportTicketCategory->whereIn('id', $this->request->ids)->get();
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $message = null;
            switch ($this->request->action) {
                case 'active':
                    $this->supportTicketCategory->whereIn('id', $this->request->ids)->update([
                        "status" => 1
                    ]);
                    $message = 'active';
                    break;
                case 'deactive':
                    $this->supportTicketCategory->whereIn('id', $this->request->ids)->update([
                        "status" => 0
                    ]);
                    $message = 'deactive';
                    break;
            }


            DB::commit();
            toast('Data ' . $message . ' Successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            // Alert::error('error', 'Something went wrong ! try again later.');

            Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }
}
