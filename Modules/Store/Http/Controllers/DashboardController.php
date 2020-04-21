<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Charts, DB, Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

class DashboardController extends Controller
{


    /**
     * Get job report detail.
     * @return Response
     */
    public function getDashboaordOrderReport(Request $request)
    {
        
        $productCount = Product::where('product.status', '!=',
        'deleted')->join('store_product_price', function($join){
            $join->on('product.id', 'store_product_price.product_id');
            $join->where('store_product_price.store_id', Auth::user()->store_id);
        })->count();
        $categoryCount = Category::where('category.status', '!=',
        'deleted')->join('store_category', function($join){
            $join->on('category.id', 'store_category.cat_id');
            $join->where('store_category.store_id', Auth::user()->store_id);
        })->count();
        $orderCount = Order::where('status', '!=',
        'deleted')->where('store_id', Auth::user()->store_id)->count();
        
        $html = '<div class="main-section" >
                        <div class="dashbord dashbord-green">
                            <div class="icon-section">
                                <i class="fa fa-money" aria-hidden="true"></i><br>
                                <small>Orders</small>
                                <p>$'.$orderCount.'</p>
                            </div>
                            <div class="detail-section">
                                <a href="'.url('store/orders').'">More Info</a>
                            </div>
                        </div>
                        <div class="dashbord dashbord-red">
                            <div class="icon-section">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i><br>
                                <small>Products</small>
                                <p>'.$productCount.'</p>
                            </div>
                            <div class="detail-section">
                                <a href="'.url('store/product/list').'">More Info</a>
                            </div>
                        </div>      
                        <div class="dashbord dashbord-blue">
                            <div class="icon-section">
                                <i class="fa fa-tasks" aria-hidden="true"></i><br>
                                <small>Category</small>
                                <p>'.$categoryCount.'</p>
                            </div>
                            <div class="detail-section">
                                <a href="'.url('store/manage-category').'">More Info</a>
                            </div>
                        </div>
                                    
                    </div>';
        
        $Order = Order::where(DB::raw("(DATE_FORMAT(created_at,'%Y'))"),date('Y'))->where('store_id', Auth::user()->store_id);
        $ordermonth = clone $Order;
        $orderweek = clone $Order;

        $firstday =  date("Y-m-d", strtotime("first day of this month"));
        $lastday =  date("Y-m-d", strtotime("last day of this month"));
        $month = $ordermonth->whereBetween('created_at', [$firstday, $lastday])->get();
        $completeOrder =  $Order->get();
       
        $completechart = Charts::database($completeOrder, 'bar', 'highcharts')
                  ->title("Total Orders")
                  ->elementLabel("Total Orders")
                  ->values($completeOrder->pluck('total')->all())
                  ->dimensions(1000, 500)
                  ->responsive(false)
                  ->groupByMonth(date('Y'));
       $monthchart = Charts::database($month, 'bar', 'highcharts')
                  ->title("Total Orders of this Month")
                  ->elementLabel("Total Orders")
                  ->dimensions(1000, 500)
                  ->responsive(false)
                  ->groupByDay();
        return view('store::dashboard.index',compact('html','completechart', 'monthchart'));
      
    }

    
}
