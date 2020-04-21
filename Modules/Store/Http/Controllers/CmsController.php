<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use View;
use Response,Session;

use App\Repositories\CmsRepository;
use Modules\Admin\Http\Requests\EditCmsRequest;

class CmsController extends Controller
{
    public function __construct(CmsRepository $cmsRepository)
    {
        $this->cms = $cmsRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('store::manage-cms.index');

    }

    public function loadCmsList(Request $request) {

        $cms = $this->cms->getAllCms($request); // for get all faqs
        $html = View::make('store::manage-cms._list', ['cms' => $cms])->render();
        return Response::json(['success' => true, 'html' => $html]);
    }

    public function getCmsPageDetail($id){
        $editCms = $this->cms->showEditCms($id); // for show edit cms by cms id
        return view('store::manage-cms.edit-cms', ['data' => $editCms]);
    }

    public function updateCmsPageDetail(Request $request){

        try{
            $response = $this->cms->updateCmsPage($request);

            if($response['success']){
             
                $notification = array(
                    'message' => 'Content updated successfully', 
                    'alert-type' => 'success'
                );
            }
            else{
                 Session::flash('error', 'Something went wrong');
                Session::flash('alert-class', 'alert-danger'); 
            }
            return redirect('/store/manage-cms')->with($notification);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'data' => [], 'error' => ["message" => $e->getMessage()]]);
        }

    }

}
