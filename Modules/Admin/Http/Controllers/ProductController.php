<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddProductRequest;
use Modules\Admin\Http\Requests\EditProductRequest;
use App\Repositories\ProductRepository;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use File;
use DB;

use View;

class ProductController extends Controller
{
    private $ProductRepository;

    public function __construct(ProductRepository $ProductRepository)
    {
        $this->ProductRepository = $ProductRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::product.index');
    }
    public function getProductList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ProductRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
    public function addProduct()
    {
        $adminDetail = Auth::user();
        return view('admin::product.add', ['adminDetail' => $adminDetail]);
    }

    public function getEditProductDetails($id)
    {
        try {

            $data = $this->ProductRepository->getDetail($id);
            // print_r($data);            die;
            return view('admin::product.edit', ['detail' => $data]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function saveProduct(AddProductRequest $request)
    {
            
        try {
            $result = $this->ProductRepository->create($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function updateProduct(EditProductRequest $request)
    {
        try {
            return $data = $this->ProductRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            return $this->ProductRepository->delete($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getProductDetails($id)
    {
        try {
            $data = $this->ProductRepository->getDetail($id);
            return View::make('admin::product.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewProduct($id)
    {
        $user = Product::where('id', $id)->first();
        return view('admin::product.view', ['category' => $user]);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->ProductRepository->changeStatus($request);
    }


    /**
     * Get Ajax Request and restun Data
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubCategoryList($id)
    {
        $cities = DB::table("sub_category")
            ->where("category_id", $id)
            ->where("status", 'active')
            ->pluck("name", "id"); // pluck is used to get list of array of selected key
        return json_encode($cities);
    }
}
