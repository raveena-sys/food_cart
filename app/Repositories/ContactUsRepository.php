<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Datatables;
use App\Common\Helpers;
use App\Models\ContactUs;
use App\Models\User;
use \DB;

/**
 * Class EmployeeRepository.
 */

class ContactUsRepository
{
    private $ContactUs, $user;

    public function __construct(ContactUs $ContactUs) {
        $this->ContactUs = $ContactUs;
    }



    public function create($request)
    {
        DB::beginTransaction();
        try {

            $post['first_name'] = $request['first_name'];
            $post['last_name'] = $request['last_name'];
            $post['email'] = $request['email'];
            $post['phone_number'] = $request['phone_number'];
            $post['company_name'] = $request['company_name'];
            $post['interest_area'] = implode(', ', $request['interest']);

            $this->ContactUs->create($post);
            $admin_email = User::where('user_role', 'admin')->first();
            $data = [];
            $data['request'] = 'contact_us';
            $data['name'] = $post['first_name'].' '.$post['last_name'];
            $data['email'] = $post['email'];
            $data['company_name'] = $post['company_name'];
            $data['interest_area'] = $post['interest_area'];
            $data['admin_email'] = 'raveenajadon304@gmail.com';//isset($admin_email->email)?$admin_email->email:'raveena1@mailinator.com';
            $data['phone_number'] = $post['phone_number'];
            $data['subject'] = "Customer (Contact Us)";
            $mail = sendMail($data);  
            DB::commit();
            $message = "Message has been sent successfully!";
            $response = ['success' => true, 'message' => $message, 'error' => [], 'data' => []];
            //return $response;
            return redirect('/contact_us')->with('success',  $message);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }

    /*
    * Change user status by Id
    */



}
