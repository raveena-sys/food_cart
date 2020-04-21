<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddCategoryRequest;
use Modules\Admin\Http\Requests\EditCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Models\Category;
use App\Models\StoreMaster;
use App\Repositories\StoreMasterRepository;

use View, Mail;

class CategoryController extends Controller
{
    private $category;

    public function __construct(CategoryRepository $category, StoreMasterRepository $store)
    {
        $this->category = $category;
        $this->store = $store;
    }
    public function testmail(Request $request) {
        $data['name'] = 'test';
        $data['link'] = 'test';
         $mail = Mail::send('emails.admin_forgot_password', ['data' => $data], function ($message) use ($data) {
                    $message->to('raveena@mailinator.com')
                        ->from('raveena.synsoft@gmail.com', 'FoodCart')
                        ->subject('test');
                });
         return $mail;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $store = StoreMaster::where('status','active')->get();
        return view('admin::manage-master.category.index', compact('store'));
    }

    public function getCategoryList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->category->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addCategory(AddCategoryRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->category->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }


    public function updateCategory(EditCategoryRequest $request)
    {
        try {
            return $data = $this->category->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteCategory(Request $request)
    {
        try {
            return $this->category->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function getCategoryDetails($id)
    {
        try {
            $store = StoreMaster::where('status','active')->get();
            $data = $this->category->getDetail($id);
            return View::make('admin::manage-master.category.edit', ['detail' => $data, 'store' => $store])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewCategory($id)
    {
        $user = Category::where('id', $id)->first();
        return view('admin::manage-master.category.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->category->changeStatus($request);
    }
}
