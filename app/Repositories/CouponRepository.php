<?php

namespace App\Repositories;
use App\Models\DiscountCoupon;
use Datatables, Response, File, Str, Auth;

class CouponRepository
{
	public function list($request){
		try{
			$coupon = DiscountCoupon::where('status', '!=', 'deleted')->where('store_id',  Auth::user()->store_id);

			if (!empty($request->status)) {
                $coupon->where('status', $request->status);
            }

            $coupon = $coupon->latest()->get();
            $tableResult = Datatables::of($coupon)->addIndexColumn()

                ->filter(function ($instance) use ($request) {

                    if (!empty($request->name)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(Str::lower($row['coupon_code']), Str::lower(trim($request->name))) ? true : false;
                        });
                    }
                })
                ->editColumn('discount', function ($data) {
                    if($data->coupon_type == 'fixed_discount'){
                        return '$'.$data->coupon_amount;  
                    }else{
                        return $data->coupon_amount.'%';  
                    }
                })
                ->editColumn('expired_at', function ($data) {
                    return $data->expired_at;
                })
                
                ->addColumn('status', function ($row) {

                    $status = isset($row->status) ? $row->status : "";
                    $checked = ($status == 'active') ? "checked" : "";

                    $btn = "<div class='switch'> <label> <input class='onoffswitch-checkbox switchchange' name='onoffswitch' type='checkbox' data-status='$status' id='category$row->id' $checked  onclick='changeStatus($row->id)' data-tableid='employee-lenderslist'> <span class='lever'></span> </label> </div>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $viewUrl = url('store/manage-coupon/view/' . $row->id);
                    $editURL = url('store/manage-coupon/edit/' . $row->id);

                    $btn = "";
                    $btn = '<div class="dropdown">
                           <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="icon-keyboard_control"></span>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               
                               <a class="dropdown-item" href="' . $editURL . '"   >Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"  id=remove' . $row->id . ' data-url=' . url('store/manage-coupon') . 'sdata-tableid="data-listing" onclick="deleteCoupon(' . $row->id . ')" >Delete</a>
                           </div>
                       </div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action', 'name'])
                ->make(true);

            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $tableResult];
            return $response;
			return $coupon;

		}catch(Exception $e){
			return Response::json(['status'=> 201, 'message' => 'Something went wrong!']);
		}

	}
	public function create($request){
		try{  
            $fileName = "";
            $profilePath = public_path() . '/uploads/coupon';
            if (!is_dir($profilePath)) {
                File::makeDirectory($profilePath, $mode = 0777, true, true);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $name = time().'.'.$fileExtension;
                $imageExist = $profilePath.'/' . $fileName;
                $request->file('image')->move($profilePath, $name);

            }else{
                $name = $request->coupon_image;
            }
			$post['coupon_image'] = $name;
            $post['store_id'] = $request->store_id;
            $post['coupon_code'] = $request->coupon_code;
			$post['coupon_type'] = $request->discount_type;
			$post['coupon_amount'] = $request->coupon_amount;
			$post['expired_at'] = $request->expired_at;
			$post['status'] = 'active';
			$coupon = DiscountCoupon::updateOrCreate(['id'=>$request->id],$post);
            if(!empty($request->id)){
                $img = getUserImage('');
                $msg = 'Coupon updated successfully';
            }else{
                $img = getUserImage('');
                $msg = 'Coupon created successfully';
            }
			return ['status' => 200, 'message' =>$msg, 'img' =>$img];
		}catch(Exception $e){
			return ['status' => 201, 'message' => 'Something went wrong'];
		}
	}
	public function status($request){
        try{
            $coupon = DiscountCoupon::where(['id' => $request->id])->first();

            if(isset($request->status)){
                $coupon->status = $request->status;
                $coupon->save();
            }
    	    if($request->status == 'deleted'){
                $msg = 'Coupon deleted successfully';
            }else{

                $msg = 'Coupon updated successfully';
            }
            return ['status' => 200, 'message' => $msg];
        }catch(Exception $e){
            return ['status' => 201, 'message' => 'Something went wrong'];
        }
	}
    public function getcouponDetail($id){
        try{  
            $coupon = DiscountCoupon::where(['id' => $id])->first();
            return $coupon;
        }catch(Exception $e){
            return ['status' => 201, 'message' => 'Something went wrong'];
        }
    }

}