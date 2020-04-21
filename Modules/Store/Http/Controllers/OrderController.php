<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddOrderRequest;
use Modules\Admin\Http\Requests\EditOrderRequest;
use App\Repositories\OrderRepository;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use File;
use DB;
use View;

class OrderController extends Controller
{
    private $OrderRepository;

    public function __construct(OrderRepository $OrderRepository)
    {
        $this->OrderRepository = $OrderRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('store::orders.index');
    }
    public function getOrderList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->OrderRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
    public function addOrder()
    {
        return view('store::orders.add', ['orderDetail' => $orderDetail]);
    }

    public function getEditOrderDetails($id)
    {
        try {

            $data = $this->OrderRepository->getDetail($id);
            // print_r($data);            die;
            return view('store::orders.edit', ['detail' => $data]);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function saveOrder(AddOrderRequest $request)
    {
            
        try {
            $result = $this->OrderRepository->create($request);
            return $result;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function updateOrder(EditOrderRequest $request)
    {
        try {
            return $data = $this->OrderRepository->update($request);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteOrder($id)
    {
        try {
            return $this->OrderRepository->delete($id);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getOrderDetails($id)
    {
        try {
            $data = $this->OrderRepository->getDetail($id);
            return View::make('store::orders.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewOrder($id)
    {
        $order = $this->OrderRepository->getDetail($id);
        return view('store::orders.view', compact('order'));
    }

    public function getLatestOrder()
    {
        $order = $this->OrderRepository->getLatestOrder();
        return $order;
    }


    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->OrderRepository->changeStatus($request);
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
            ->pluck("name", "id"); // pluck is used to get list of array of selected key
        return json_encode($cities);
    }


}
