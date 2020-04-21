<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Datatables;
use App\Common\Helpers;
use App\Http\Requests\CheckoutRequest;

use App\Models\Checkout;
use \DB;

/**
 * Class EmployeeRepository.
 */

class CheckoutRepository
{
    private $Checkout, $user;

    public function __construct(

        Checkout $Checkout
    ) {

        $this->Checkout = $Checkout;
    }


    public function create($request)
    {


        DB::beginTransaction();
        try {

            $post['name'] = $request['name'];
            $post['address'] = $request['address'];
            $post['email'] = $request['email'];
            $post['phone_no'] = $request['phone_no'];
            $post['city'] = $request['city'];
            $post['province'] = $request['province'];
            $post['province1'] = $request['province1'];


            $this->Checkout->create($post);

            DB::commit();
            $message = "Checkout information added sucsessfully.";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            //return $response;
            return redirect('checkout')->with('success',  $message);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }
}
