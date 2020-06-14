<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUsRequest;

use App\Repositories\ContactUsRepository;

use Illuminate\Support\Facades\Auth;
use App\Models\ContactUs;
use App\Models\Cms;

use DB;

class ContactUsController extends Controller
{
    private $ContactUsRepository;

    public function __construct(ContactUsRepository $ContactUsRepository)
    {
        $this->ContactUsRepository = $ContactUsRepository;
    }

   /* public function index()
    {
        try {
            //  $aboutus = $this->cms->where(['page_name'=>'about_us'])->first();
            //  return view('home', ['aboutus' => $aboutus]);
            return view('dashboard');
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }*/


    public function contactus()
    {
        try {
            $cms = Cms::where(['page_slug'=>'contact_us'])->first();
            return view('front.contact_us', compact('cms'));
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function addContactUs(Request $request)
    {

        try {
            return $this->ContactUsRepository->create($request);
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['success' => false, 'message' => '', 'error' => [array('message' => $e->getMessage())], 'data' => []];
            return $response;
        }
    }





}
