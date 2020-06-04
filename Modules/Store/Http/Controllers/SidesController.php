<?php
namespace Modules\Store\Http\Controllers;

use Illuminate\Routing\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Repositories\SidesRepository;
use App\Models\Product;
use Response, Session;


class SidesController extends Controller
{
	private $SidesRepository;

    public function __construct(SidesRepository $SidesRepository)
    {
        $this->SidesRepository = $SidesRepository;
    }
	public function index(){ 
		return view('store::manage-sides-menu.index');
	}

	public function getList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->SidesRepository->getList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }


	public function add(){
		$adminDetail = Auth::user();
        return view('store::manage-sides-menu.add', ['adminDetail' => $adminDetail]);
	}

	public function create(Request $request)
    {
    
        try {
            $result = $this->SidesRepository->create($request);
            if( $result['success']){
                Session::flash('message', 'Sides menu created successfully');
                return redirect('store/manage-sides-menu/');
            }

        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

}