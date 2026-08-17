<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActivityEventType;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogSubCategory;
use App\Traits\ActivityLogger;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class AdminBlogController extends Controller
{
    use ApiResponseTrait, ActivityLogger;
    public function __construct(
        public Request $request,
        public Blog $blog,
        public BlogCategory $blogCategory,
        public BlogSubCategory $blogSubCategory
    ) {

    }
    public function index()
    {
        $limit = $this->request->input('limit', 10);
        $page = $this->request->input('page', 0);
        $query = $this->blog->query();
        $query->search($this->request->search)
            ->status($this->request->status)
            ->filterDateOrder($this->request->date);
        $data = $query->paginate($limit, ['*'], 'page', $page);
        if ($this->request->ajax()) {
            return view('admin.blog.table', compact('data'));
        }
        return view('admin.blog.index', compact('data'));
    }

    public function subCategoriesByCategory($category)
    {
        try {
            $subcategories = $this->blogSubCategory->where('category', $category)
                ->where('status', 1)->pluck('title');
            return $this->dataResponse([
                "data" => $subcategories,

            ], "Data fetched successfully !");
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function create()
    {
        try {
            $categories = $this->blogCategory->where('status', 1)->pluck('title');
            return view('admin.blog.create', compact('categories'));
        } catch (\Throwable $th) {
            //throw $th;

            Alert::error('error', 'Something went wrong ! try again later.');
            // Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }
    public function store()
    {

        $this->request->validate([
            'title' => 'required|string|max:225',
            'category' => 'required|string|max:225',
            'sub_category' => 'required|string|max:225',
            'content' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $blog = $this->blog;
            $blog->user_id = Auth::id();
            $blog->title = $this->request->title;
            $blog->slug = Str::slug($this->request->title) . '-' . time();
            $blog->category = $this->request->category;
            $blog->sub_category = $this->request->sub_category;
            $blog->content = $this->request->input('content');
            $blog->status = 1;
            $blog->save();

            $this->activity()->event("Blog")
                ->eventType(ActivityEventType::CREATED)
                ->description(ActivityEventType::CREATED)
                ->isAdmin()
                ->properties([
                    "data" => $blog
                ])->save();

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

    public function edit($id)
    {
        try {
            $data = $this->blog->where('id', $id)->first();
            if ($data == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $categories = $this->blogCategory->where('status', 1)->pluck('title');
            $subcategories = $this->blogSubCategory->where('category', $data->category)
                ->where('status', 1)->pluck('title');
            return view('admin.blog.edit', compact('data', 'categories', 'subcategories'));
        } catch (\Throwable $th) {
            //throw $th;

            Alert::error('error', 'Something went wrong ! try again later.');
            // Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }
    public function update($id)
    {


        $this->request->validate([
            'title' => 'required|string|max:225',
            'category' => 'required|string|max:225',
            'sub_category' => 'required|string|max:225',
            'content' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (trim(strip_tags($value)) === '') {
                        $fail('Content is required.');
                    }
                },
            ],
        ]);

        DB::beginTransaction();
        try {
            $blog = $this->blog->where('id', $id)->first();
            if ($blog == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $this->activity()->event("Blog")
                ->eventType(ActivityEventType::UPDATED)
                ->isAdmin()
                ->description("Blog data updated")
                ->oldData($blog);

            $blog->title = $this->request->title;
            $blog->slug = Str::slug($this->request->title);
            $blog->category = $this->request->category;
            $blog->sub_category = $this->request->sub_category;
            $blog->content = $this->request->input('content');
            $blog->update();

            $this->newData($blog->fresh())->save();
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

    public function updateStatus($id)
    {
        DB::beginTransaction();
        try {
            $blog = $this->blog->where('id', $id)->first();
            if ($blog == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }



            $blog->status = ($blog->status == 1 ? 0 : 1);
            $blog->save();
            $this->activity()
                ->event("Blog")
                ->isAdmin()
                ->eventType($blog->status == 1 ? ActivityEventType::STATUSENABLE : ActivityEventType::STATUSDISABLE)
                ->description("Blog disable")
                ->properties([
                    "data" => $blog->refresh()
                ])->save();
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
            $blog = $this->blog->where('id', $id)->first();
            if ($blog == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $this->activity()
                ->event("Blog")
                ->eventType(ActivityEventType::DELETED)
                ->isAdmin()
                ->description("Blog deleted")
                ->properties([
                    "data" => $blog
                ])->save();

            $blog->delete();
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
            $blog = $this->blogCategory->whereIn('id', $this->request->blogs)->get();
            if ($blog == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $message = null;
            switch ($this->request->action) {
                case 'active':
                    $this->blog->whereIn('id', $this->request->blogs)->update([
                        "status" => 1
                    ]);
                    $message = 'active';

                    $this->activity()
                        ->event("Blog")
                        ->eventType(ActivityEventType::STATUSENABLE)
                        ->isAdmin()
                        ->description("Multiple blog active")
                        ->properties([
                            "data" => $this->blog->whereIn('id', $this->request->blogs)->get()
                        ])->save();

                    break;
                case 'deactive':
                    $this->blog->whereIn('id', $this->request->blogs)->update([
                        "status" => 0
                    ]);
                    $message = 'deactive';

                    $this->activity()
                        ->event("Blog")
                        ->eventType(ActivityEventType::STATUSDISABLE)
                        ->isAdmin()
                        ->description("Multiple blog deactive")
                        ->properties([
                            "data" => $this->blog->whereIn('id', $this->request->blogs)->get()
                        ])->save();

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
