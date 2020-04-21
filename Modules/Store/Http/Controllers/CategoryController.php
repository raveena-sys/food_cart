<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddCategoryRequest;
use Modules\Admin\Http\Requests\EditCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Models\Category;
use App\Models\StoreMaster;
use App\Repositories\StoreMasterRepository;

use View;

class CategoryController extends Controller
{
    private $category;

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;
        //$this->store = $store;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        //$store = StoreMaster::where('status','active')->get();
        return view('store::manage-master.category.index');
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
            //$store = StoreMaster::where('status','active')->get();
            $data = $this->category->getDetail($id);

            return View::make('store::manage-master.category.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewCategory($id)
    {
        $user = $this->category->getDetail($id);
        return view('store::manage-master.category.view', ['category' => $user]);
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

    public function getCustomCategory(Request $request){
      
        $products = Category::select("id", "name")->where(['status' => 'active', 'store_id' => Null]);

        if($request->id){
            $idArray = explode(',', $request->id);
            $products->whereNotIN("id", $idArray);
        }
        
        $data['category'] = $products->get();
        $data['selected_ids'] ='';
        return view('store::manage-master.category.categoryselection')->with($data);
    }
    
    public function addselection(Request $request){

        try {
            $result = $this->category->storeCategorySelection($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

}
