<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\StoreMaster;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Auth;
use DB;
use App\Common\Helpers;

//use Your Model

/**
 * Class DashboardRepository.
 */
class AdminRepository 
{
    
    /**
     * @return string
     *  Return the model
     */
    

    public function getDashboardCardCount($request)
    {
        try {
            $data['storeCount'] = StoreMaster::where('status', '!=', 'deleted')->count();
            $data['productCount'] = Product::where('status', '!=', 'deleted')->count();
            $data['categoryCount'] = Category::where('status', '!=', 'deleted')->count();
            $data['orderCount'] = Order::where('status', '!=', 'deleted')->count();
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $data];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    public function getDashboaordOrderReport($request)
    {
        try {
            $todayDate = date('Y-m-d');
            DB::enableQueryLog();
            $orderDetail = Order::select(DB::raw('DATE(created_at) as date'),DB::raw('count(*) as views'));
            
            if (!empty($request->range) && empty($request->from_date) && empty($request->from_date)) {
                if ($request->range == 'today') {
                    $todayDate = date('Y-m-d');
                    $from = $todayDate . ' 00:00:00';
                    $to = $todayDate . ' 23:59:00';
                    $orderDetail->where('created_at', '>=',$from);
                    $orderDetail->where('created_at','<=',$to);
                }

                if ($request->range == 'this_week') {
                    $firstday = date('Y-m-d', strtotime("this week"));
                    $lastday = date('Y-m-d', strtotime($firstday . ' + 6 days'));
                    $from = $firstday . ' 00:00:00';
                    $to = $lastday . ' 23:59:00';
                    $orderDetail->whereBetween('created_at', [$from, $to]);
                 }

                if ($request->range == 'this_month') {
                    $firstday =  date("Y-m-d", strtotime("first day of this month"));
                    $lastday =  date("Y-m-d", strtotime("last day of this month"));
                    $orderDetail->whereBetween('created_at', [$firstday, $lastday]);
                }
            }

            if (!empty($request->from_date) && !empty($request->to_date)) {
                $from = date('Y-m-d', strtotime($request->from_date));
                $to = date('Y-m-d', strtotime($request->to_date));
                $fromDate = $from . ' 00:00:00';
                $toDate = $to . ' 23:59:00';
                $orderDetail->whereBetween('created_at', [$fromDate, $toDate]);
            }
            $reportData =  $orderDetail/*->groupby('date')*/->get();
            /*$chart = array();
            foreach ($reportData as $key => $value) {
                $chart[] = $value->date.','. $value->views.", '#3CC9D7'";
            }*/

            //dd(DB::getQueryLog());
            $response = ['success' => true, 'message' => '', 'error' => [], 'data' => $reportData];
            return $response;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
