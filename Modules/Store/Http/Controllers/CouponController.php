<?php 
namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CouponRepository;
use Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\AddStoreMasterRequest;
use App\Models\DiscountCoupon;
use Session;
use View;

class CouponController extends Controller
{
    public function __construct(CouponRepository $CouponRepository){
        $this->CouponRepository = $CouponRepository;
    }
    public function index(){
        return view('store::manage-coupon.index');
    }

    public function add(){

        return view('store::manage-coupon.add');
    }

    public function list(Request $request){
        
        try {
            $result = $this->CouponRepository->list($request);
            return $result['data'];
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function create(Request $request){
        try {
            $data = $this->CouponRepository->create($request);            
            return $data;
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    

    public function detail($id)
    {
        
        try {
            $data = $this->CouponRepository->getcouponDetail($id);

            return view('store::manage-coupon.add', compact('data'));
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }



    public function coupon_zone_status(Request $request)
    {
        return $this->CouponRepository->coupon_zone_status($request);
    }

}