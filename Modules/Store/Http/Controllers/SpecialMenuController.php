<?php
namespace Modules\Store\Http\Controllers;

use Illuminate\Routing\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Models\Product;
use Response;


class SpecialMenuController extends Controller
{
	private $ProductRepository;

    public function __construct(ProductRepository $ProductRepository)
    {
        $this->ProductRepository = $ProductRepository;
    }
	public function index(){ 

		return view('store::manage-special-menu.index');
	}

	public function getProductList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->ProductRepository->getSpecialProductList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }


	public function add(){
		$adminDetail = Auth::user();
        return view('store::manage-special-menu.add', ['adminDetail' => $adminDetail]);
	}

	public function create(Request $request)
    {
    
        try {
            $result = $this->ProductRepository->createSpecialProduct($request);
            return $result;
           

        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function edit(Request $request){
        $adminDetail = Auth::user();
        $id = $request->segment(5);
        $result = $this->ProductRepository->getDetail($id);
        return view('store::manage-special-menu.add', ['adminDetail' => $adminDetail, 'detail'=> $result]);
    }
    public function view(Request $request){
        $adminDetail = Auth::user();
        $id = $request->segment(5);
        $result = $this->ProductRepository->getDetail($id);
        return view('store::manage-special-menu.view', ['adminDetail' => $adminDetail, 'data'=> $result]);
    }

    
    public function uploadImage(Request $request){
        $fileName = "";
        $profilePath = public_path() . '/uploads/sides';
        if (!is_dir($profilePath)) {
            File::makeDirectory($profilePath, $mode = 0777, true, true);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $name = time().'.'.$fileExtension;
            $imageExist = public_path() . '/uploads/sides/' . $name;
            $request->file('image')->move($profilePath, $name);
            $post['store_image'] = $name;
            StoreProductPrice::updateOrCreate(['product_id'=>$post['product_id'], 'store_id'=>$post['store_id']], $post);
        }
    }


}