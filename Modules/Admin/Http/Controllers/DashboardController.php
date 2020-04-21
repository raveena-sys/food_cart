<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Charts, DB;
use App\Models\StoreMaster;
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
        $storeCount = StoreMaster::where('status', '!=', 'deleted')->count();
        $productCount = Product::where('status', '!=', 'deleted')->count();
        $categoryCount = Category::where('status', '!=', 'deleted')->count();
        $orderCount = Order::where('status', '!=', 'deleted')->count();
        
        $html = '<div class="main-section" >
                            <div class="dashbord dashbord-skyblue">
                                
                                <div class="icon-section">
                                    <i class="fa fa-users" aria-hidden="true"></i><br>
                                    <small>Store</small>
                                    <p>'.$storeCount.'</p>
                                </div>
                                <div class="detail-section">
                                    <a href="'.url('admin/manage-store/list').'">More Info</a>
                                </div>
                                
                            </div>
                            <div class="dashbord dashbord-green">
                                <div class="icon-section">
                                    <i class="fa fa-money" aria-hidden="true"></i><br>
                                    <small>Orders</small>
                                    <p>$'.$orderCount.'</p>
                                </div>
                                <div class="detail-section">
                                    <a href="'.url('admin/orders').'">More Info</a>
                                </div>
                            </div>
                            <div class="dashbord dashbord-red">
                                <div class="icon-section">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i><br>
                                    <small>Products</small>
                                    <p>'.$productCount.'</p>
                                </div>
                                <div class="detail-section">
                                    <a href="'.url('admin/product/list').'">More Info</a>
                                </div>
                            </div>      
                            <div class="dashbord dashbord-blue">
                                <div class="icon-section">
                                    <i class="fa fa-tasks" aria-hidden="true"></i><br>
                                    <small>Category</small>
                                    <p>'.$categoryCount.'</p>
                                </div>
                                <div class="detail-section">
                                    <a href="'.url('admin/manage-category').'">More Info</a>
                                </div>
                            </div>
                                
                 </div>';
        
        $Order = Order::where(DB::raw("(DATE_FORMAT(created_at,'%Y'))"),date('Y'));
        $ordermonth = clone $Order;
        $orderweek = clone $Order;

        //if ($request->range == 'today') {
            /*$todayDate = date('Y-m-d');
            $from = $todayDate . ' 00:00:00';
            $to = $todayDate . ' 23:59:00';
            $Order->where('created_at', '>=',$from);
            $Order->where('created_at','<=',$to);*/


        //}

        /*if ($request->range == 'this_week') {
            $firstday = date('Y-m-d', strtotime("this week"));
            $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
            $from = $firstday . ' 00:00:00';
            $to = $lastday . ' 23:59:00';
            $week = $orderweek->whereBetween('created_at', [$from, $to])->get();
        }*/

        //if ($request->range == 'this_month') {
            $firstday =  date("Y-m-d", strtotime("first day of this month"));
            $lastday =  date("Y-m-d", strtotime("last day of this month"));
            $month = $ordermonth->whereBetween('created_at', [$firstday, $lastday])->get();
            $completeOrder =  $Order->get();
        //}


        /*if (!empty($request->from_date) && !empty($request->to_date)) {
            $from = date('Y-m-d', strtotime($request->from_date));
            $to = date('Y-m-d', strtotime($request->to_date));
            $fromDate = $from . ' 00:00:00';
            $toDate = $to . ' 23:59:00';
            $orderDetail->whereBetween('created_at', [$fromDate, $toDate]);
        }*/
                    
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
        return view('admin::dashboard.index',compact('html','completechart', 'monthchart'));
      
    }

    
}
