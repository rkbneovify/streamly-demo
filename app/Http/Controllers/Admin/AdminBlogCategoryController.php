<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AdminBlogCategoryController extends Controller
{
    public function __construct(public Request $request, public BlogCategory $blogCategory) {}

    public function index()
    {

        try {
            $limit = $this->request->input('limit', 10);
            $page = $this->request->input('page', 1);
            $query = $this->blogCategory->query();
            $query->status($this->request->status)
                ->search($this->request->search)
                ->filterDateOrder($this->request->date)
                ->latest();
            $data = $query->paginate($limit, ['*'], 'page', $page);
            // return response()->json($data);
            if ($this->request->ajax()) {
                return view('admin.blog.category.table', compact('data'));
            }
            return view('admin.blog.category.index', compact('data'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function store()
    {
        $validator = validator($this->request->all(), [

            'title' => 'required|string|unique:blog_categories,title',
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
            $category = $this->blogCategory;
            $category->title = $this->request->title;
            $category->status = 1;
            $category->save();
            DB::commit();
            toast('Data saved Successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error('error', 'Something went wrong ! try again later.');

            // Alert::error('Oops !', $th->getMessage());
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
            $category = $this->blogCategory->where('id', $id)->first();
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $category->title = $this->request->title;
            $category->save();
            DB::commit();
            toast('Data updated Successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->route('admin.blog.category');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error('error', 'Something went wrong ! try again later.');

            // Alert::error('Oops !', $th->getMessage());
            return redirect()->route('admin.blog.category');
        }
    }

    public function updateStatus($id)
    {
        DB::beginTransaction();
        try {
            $category = $this->blogCategory->where('id', $id)->first();
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $category->status = ($category->status == 1 ? 0 : 1);
            $category->save();
            DB::commit();
            toast('Data updated Successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error('error', 'Something went wrong ! try again later.');

            // Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $category = $this->blogCategory->where('id', $id)->first();
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $category->delete();
            DB::commit();
            toast('Data deleted Successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error('error', 'Something went wrong ! try again later.');

            // Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function bulkAction()
    {

        DB::beginTransaction();
        try {
            $category = $this->blogCategory->whereIn('id', $this->request->categories)->get();
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $message = null;
            switch ($this->request->action) {
                case 'active':
                    $this->blogCategory->whereIn('id', $this->request->categories)->update([
                        "status" => 1
                    ]);
                    $message = 'active';
                    break;
                case 'deactive':
                    $this->blogCategory->whereIn('id', $this->request->categories)->update([
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
