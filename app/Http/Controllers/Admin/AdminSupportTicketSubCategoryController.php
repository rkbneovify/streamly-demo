<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicketCategory;
use App\Models\SupportTicketSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AdminSupportTicketSubCategoryController extends Controller
{
    public function __construct(
        protected Request $request,
        protected SupportTicketSubCategory $supportTicketSubCategory,
        protected SupportTicketCategory $supportTicketCategory
    ) {}
    public function index()
    {
        $limit = $this->request->limit;
        $page = $this->request->page;
        $query = $this->supportTicketSubCategory->query();
        $query->statusFilter($this->request->status)
            ->dateOrderFilter($this->request->date)
            ->searchFilter($this->request->search, 'title')->latest();
        $data = $query->paginate($limit, ['*'], 'page', $page);
        $categories = $this->supportTicketCategory->where('status', 1)->get();
        if ($this->request->ajax()) {
            return view('admin.support-ticket.sub-category.table', compact('data', 'categories'));
        }

        return view('admin.support-ticket.sub-category.index', compact('data', 'categories'));
    }

    public function store()
    {



        DB::beginTransaction();
        try {
            $subcategory = $this->supportTicketSubCategory;
            $subcategory->category = $this->request->category;
            $subcategory->title = $this->request->title;
            $subcategory->status = 1;
            $subcategory->save();
            toast('Data saved successfully !', 'success')
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

    public function update($id)
    {
        DB::beginTransaction();
        try {
            $subcategory = $this->supportTicketSubCategory->find($id);
            if ($subcategory == null) {
                toast('Data not found !', 'erorr')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $subcategory->category = $this->request->category;
            $subcategory->title = $this->request->title;
            $subcategory->save();
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
            $subcategory = $this->supportTicketSubCategory->find($id);
            if ($subcategory == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $message = $subcategory->status == 1 ? "inactivated" : "activated";

            $subcategory->status =  $subcategory->status == 1 ? 0 : 1;
            $subcategory->save();
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
            $subcategory = $this->supportTicketSubCategory->find($id);
            if ($subcategory == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }


            $subcategory->delete();
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
            $subcategory = $this->supportTicketSubCategory->whereIn('id', $this->request->ids)->get();
            if ($subcategory == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $message = null;
            switch ($this->request->action) {
                case 'active':
                    $this->supportTicketSubCategory->whereIn('id', $this->request->ids)->update([
                        "status" => 1
                    ]);
                    $message = 'active';
                    break;
                case 'deactive':
                    $this->supportTicketSubCategory->whereIn('id', $this->request->ids)->update([
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
