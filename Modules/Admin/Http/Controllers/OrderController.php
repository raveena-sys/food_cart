<?php

namespace Modules\Admin\Http\Controllers;

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
use View, PDF;

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
        return view('admin::orders.index');
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
        return view('admin::orders.add', ['orderDetail' => $orderDetail]);
    }

    public function getEditOrderDetails($id)
    {
        try {

            $data = $this->OrderRepository->getDetail($id);
            // print_r($data);            die;
            return view('admin::orders.edit', ['detail' => $data]);
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
            return View::make('admin::orders.edit', ['detail' => $data])->render();
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewOrder($id)
    {
        $order = $this->OrderRepository->getDetail($id);
        return view('admin::orders.view', compact('order'));
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

    public function printOrder($id)
    {
        try {
            $orderdata = $this->OrderRepository->getDetail($id);
            $pdf = PDF::loadView('front.invoice.order_invoice', compact('orderdata'));
            $path = 'public/uploads/order_invoice';
            $file = '/'.str_pad($orderdata->id,6,"0", STR_PAD_LEFT).'.pdf';
            if(!file_exists($path)){
                mkdir($path, 0777, true);
            }
            $res = $pdf->save($path.$file);
            return Response::json(['success' => true, 'url' => url($path.$file)]);
           
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
