<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddSubCategoryRequest;
use Modules\Admin\Http\Requests\EditSubCategoryRequest;
use App\Repositories\SubCategoryRepository;
use App\Models\SubCategory;
use View;

class SubCategoryController extends Controller
{
    private $SubCategory;

    public function __construct(SubCategoryRepository $SubCategory)
    {
        $this->SubCategory = $SubCategory;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('store::manage-master.sub-category.index');
    }
    public function getSubCategoryList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->SubCategory->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addSubCategory(AddSubCategoryRequest $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->SubCategory->create($request);
                return $result;
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }


    public function updateSubCategory(EditSubCategoryRequest $request)
    {
        try {
            return $data = $this->SubCategory->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteSubCategory(Request $request)
    {
        try {
            return $this->SubCategory->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function getSubCategoryDetails($id)
    {
        try {
            $data = $this->SubCategory->getDetail($id);
            return View::make('store::manage-master.sub-category.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewSubCategory($id)
    {
        $user = SubCategory::where('id', $id)->first();
        return view('store::manage-master.sub-category.view', ['SubCategory' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->SubCategory->changeStatus($request);
    }
}
