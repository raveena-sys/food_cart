<?php
namespace Modules\Store\Http\Controllers;

use Illuminate\Routing\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Repositories\PizzaSauceRepository;
use App\Repositories\PizzaSizeRepository;
use App\Repositories\PizzaCrustRepository;
use App\Repositories\ToppingWingFlavourRepository;
use App\Repositories\ToppingDonairShawarmaMediterraneanRepository;
use App\Models\Product;
use Response;


class SpecialMenuController extends Controller
{
	private $product;
    private $PizzaSauce;
    private $PizzaCrust;
    private $PizzaSize;
    private $ToppingWing;
    private $ToppingDonair;

    public function __construct(ProductRepository $product, PizzaSauceRepository $PizzaSauce, PizzaCrustRepository $PizzaCrust , PizzaSizeRepository $PizzaSize, ToppingWingFlavourRepository $ToppingWing,  ToppingDonairShawarmaMediterraneanRepository $ToppingDonair)
    {
        $this->product = $product;
        $this->PizzaSauce = $PizzaSauce;
        $this->PizzaCrust = $PizzaCrust;
        $this->PizzaSize = $PizzaSize;
        $this->ToppingWing = $ToppingWing;
        $this->ToppingDonair = $ToppingDonair;
    }
	public function index(){ 

		return view('store::manage-special-menu.index');
	}

	public function getProductList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $result = $this->product->getSpecialProductList($request);
                return $result['data'];
            } catch (\Exception $e) {
                return Response::json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }


	public function add(Request $request){
		$adminDetail = Auth::user();
        $pizzaSauce = $this->PizzaSauce->getList($request);
        $data['sauce']= $pizzaSauce['data']->getData()->data;

        $pizzaCrust = $this->PizzaCrust->getList($request);
        $data['crust']= $pizzaCrust['data']->getData()->data;

        $pizzaSize = $this->PizzaSize->getList($request);
        $data['size']= $pizzaSize['data']->getData()->data;

        $toppingWing = $this->ToppingWing->getList($request);
        $data['toppingWing']= $toppingWing['data']->getData()->data;
        
        $toppingDonair = $this->ToppingDonair->getList($request);
        $data['toppingDonair']= $toppingDonair['data']->getData()->data;
        
        return view('store::manage-special-menu.add', ['adminDetail' => $adminDetail, 'topping'=> $data]);
	}

	public function create(Request $request)
    {
    
        try {
            $result = $this->product->createSpecialProduct($request);
            return $result;
           

        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function edit(Request $request){
        $adminDetail = Auth::user();
        $pizzaSauce = $this->PizzaSauce->getList($request);
        $data['sauce']= $pizzaSauce['data']->getData()->data;

        $pizzaCrust = $this->PizzaCrust->getList($request);
        $data['crust']= $pizzaCrust['data']->getData()->data;

        $pizzaSize = $this->PizzaSize->getList($request);
        $data['size']= $pizzaSize['data']->getData()->data;

        $toppingWing = $this->ToppingWing->getList($request);
        $data['toppingWing']= $toppingWing['data']->getData()->data;
        
        $toppingDonair = $this->ToppingDonair->getList($request);
        $data['toppingDonair']= $toppingDonair['data']->getData()->data;
        $id = $request->segment(5);
        $result = $this->product->getDetail($id);
        return view('store::manage-special-menu.add', ['adminDetail' => $adminDetail, 'detail'=> $result, 'topping'=> $data]);
    }
    public function view(Request $request){
        $adminDetail = Auth::user();
        $id = $request->segment(5);
        $result = $this->product->getDetail($id);
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