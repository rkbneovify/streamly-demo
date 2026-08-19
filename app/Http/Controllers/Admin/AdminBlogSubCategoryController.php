<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogSubCategory;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AdminBlogSubCategoryController extends Controller
{
    public function __construct(public Request $request, public BlogCategory $blogCategory, public BlogSubCategory $blogSubCategory)
    {
    }

    public function index()
    {

        try {
            $limit = $this->request->input('limit', 10);
            $page = $this->request->input('page', 1);
            $query = $this->blogSubCategory->query();
            $query->status($this->request->status)
                ->search($this->request->search)
                ->filterDateOrder($this->request->date)
                ->latest();
            $data = $query->paginate($limit, ['*'], 'page', $page);
            $categories = $this->blogCategory->where('status', 1)->get();

            // return response()->json(compact('data', 'categories'));
            if ($this->request->ajax()) {
                return view('admin.blog.sub-category.table', compact('data', 'categories'));
            }
            return view('admin.blog.sub-category.index', compact('data', 'categories'));
        } catch (\Throwable $th) {
            //throw $th;
            Alert::error('error', 'Something went wrong ! try again later.');
            // Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function store()
    {

        $validator = validator($this->request->all(), [
             'category' => 'required|string|max:225',
            'title' => 'required|string|unique:blog_sub_categories,title',
        ], [
            'title.unique' => 'This sub category already exists. Please use a different sub category.',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $subcategory = $this->blogSubCategory;
            $subcategory->category = $this->request->category;
            $subcategory->title = $this->request->title;
            $subcategory->status = 1;
            $subcategory->save();
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
            'category' => 'required|string|max:225',
            'title' => 'required|string|unique:blog_sub_categories,title,'. $id,
        ], [
            'title.unique' => 'This sub category already exists. Please use a different sub category.',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $subcategory = $this->blogSubCategory->where('id', $id)->first();
            if ($subcategory == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $subcategory->category = $this->request->category;
            $subcategory->title = $this->request->title;
            $subcategory->save();
            DB::commit();
            toast('Data updated successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->route('admin.blog.subcategory');
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
            $category = $this->blogSubCategory->where('id', $id)->first();
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $category->status = ($category->status == 1 ? 0 : 1);
            $category->save();
            DB::commit();
            toast('Data updated successfully !', 'success')
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
            $category = $this->blogSubCategory->where('id', $id)->first();
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $category->delete();
            DB::commit();
            toast('Data deleted successfully !', 'success')
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
            $category = $this->blogSubCategory->whereIn('id', $this->request->categories)->get();
            if ($category == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $message = null;
            switch ($this->request->action) {
                case 'active':
                    $this->blogSubCategory->whereIn('id', $this->request->categories)->update([
                        "status" => 1
                    ]);
                    $message = 'active';
                    break;
                case 'deactive':
                    $this->blogSubCategory->whereIn('id', $this->request->categories)->update([
                        "status" => 0
                    ]);
                    $message = 'deactive';
                    break;

            }


            DB::commit();
            toast('Data ' . $message . ' successfully !', 'success')
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
