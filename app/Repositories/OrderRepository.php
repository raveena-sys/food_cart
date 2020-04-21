<?php

namespace App\Repositories;

use App\EmailQueue\CreateEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Order;
use Auth;
use Datatables;
use App\Common\Helpers;
use App\Models\Job;
use App\Models\StoreMaster;
use \DB, Hash, Mail, Response;

/**
 * Class EmployeeRepository.
 */

class OrderRepository
{
    private $Order;

    public function __construct(
        Order $order
    ) {
        $this->order = $order;
    }

    public function getLatestOrder()
    {
        if(Auth::check() && Auth::user()->user_type == 'store'){
            DB::enableQueryLog();
            $query = $this->order->where('status',  'placed')->where('notified',  0)->where('store_id',  Auth::user()->store_id);
            $updateQuery = clone $query;
            $order = $query->get();
            if(!empty( $order) && count($order)>0){
                $updateQuery->update([
                    'notified' => 1
                ]);
                $response = ['success' => true];
            }else{
                $response = ['success' => false];
            }
        }else{
            $response = ['success' => false];
        }  
        return Response::json($response); 
            
    }

    public function getList($request)
    {
        try {
            $order = $this->order->where('orders.status', '!=', 'deleted');
            if ($request->segment(1)=='store') { 
                $order->join('store_master', 'store_master.id', '=', 'orders.store_id');
                $order->where('orders.store_id', Auth::user()->store_id);
                $order->select('orders.*', 'store_master.name');
            }
            if (!empty($request->status)) { 

                $order->where('status', $request->status);
            }
            $order->orderby('orders.id', 'desc');
            $order = $order->get();
            $tableResult = Datatables::of($order)->addIndexColumn()

                ->filter(function ($instance) use ($request) {

                    if (!empty($request->name)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['name']), Str::lower(trim($request->name))) ? true : false;
                        });
                    }
                })
                ->editColumn('orderid', function ($data) {
                    return "#". str_pad($data->id,6,"0", STR_PAD_LEFT);
                })
                ->editColumn('store', function ($data) use($request){
                    if($request->segment(1) =='admin'){
                        return isset($data->store->name)?$data->store->name:'';
                    }else{
                        return isset($data->store->name)?$data->store->name:'';
                    }
                })
                ->editColumn('name', function ($data) {
                    $userName = isset($data->name)?ucfirst($data->name):'';
                 
                    return $userName;
                })
                ->editColumn('email', function ($data) {
                    return $data->email;
                })

                ->editColumn('mobile', function ($data) {
                    return $data->mobile_no;
                })
                ->editColumn('total', function ($data) {
                    return '$'.$data->total;
                })
                ->editColumn('status', function ($data) {
                    return ucfirst($data->status);
                })
                ->addColumn('action', function ($row) use ($request) {
                    $segment = $request->segment(1);
                    $viewUrl = url($segment.'/orders/view/' . $row->id);
                    $printUrl = url($segment.'/orders/print/' . $row->id);
                    /*$viewUrl = url('admin/orders/view/' . $row->id);

                    */
                    $editURL = url($segment.'/orders/edit/' . $row->id);
                    $btn = "";
                    $btn = '<div class="dropdown">
                           <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="icon-keyboard_control"></span>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="' . $viewUrl . '"   >View</a>
                               
                               <a class="dropdown-item" onclick="getPrint('.$row->id.')">Print Order</a>
                               <!--a class="dropdown-item" href="' . $editURL . '"   >Edit</a-->
                                <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-url=' . url('admin/employee-delete') . ' data-name=' . $row->name . ' data-tableid="data-listing" onclick="deleteOrder(' . $row->id . ')" >Delete</a>
                           </div>
                       </div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'name'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getDetail($id)
    {
        try {
            $order = $this->order->where('id', $id)->first();
            
            return $order;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Delete user by Id
    */
    public function delete($id)
    {
        try {
            $orderData = $this->order->where('id', $id)->first();
            if (!empty($orderData)) {
                DB::beginTransaction();
                if($orderData->update(array('status' => 'deleted'))){
                    DB::commit();
                }else{
                    DB::rollback();
                }
                $message = 'Order successfully deleted.';
                $message = __('messages.something_went_wrong');
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Something went wrong', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }


    public function update($request)
    {
        DB::beginTransaction();
        try {


        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Change user status by Id
    */
    public static function changeStatus($request)
    {
        try {
            $userData = StoreMaster::where(['id' => $request->id])->select('id', 'name', 'description', 'status')->first();
            if (!empty($userData)) {
                $userData->update(array('status' => $request->status));
                $message = 'Status successfully changed.';
                $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            } else {
                $response = ['success' => false, 'message' => 'Store does not found.', 'error' => [], 'data' => []];
            }
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }



}
