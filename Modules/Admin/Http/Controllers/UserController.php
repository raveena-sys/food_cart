<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Response, Session;
use Illuminate\Routing\Controller;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    private $user;
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function login()
    {
        return view('admin::create');
    }

    /**
     * load change password form
     * @return \Illuminate\Http\Response
     */
    public function loadChangePasswordForm()
    {
        return view('admin::change-password.index');
    }
    /**
     * Delete User
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request)
    {
        return $this->user->delete($request);
    }

    /**
     * Change User Status
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        return $this->user->changeStatus($request);
    }

    /**
     * Change Social links
     * @param Request $request
     * @return Response
     */
    public function getSocialLinks(Request $request)
    {
        $data = $this->user->socialLink($request);
        return view('admin::social-icon.edit_social', compact('data'));
    }
    public function updateSocialLinks(Request $request)
    {
        $status = $this->user->updateSocialLinks($request);
        if($status['success'] == 1){    
            Session::flash('message', $status['message']);
        }else{
            Session::flash('message', $status['message']);
        }
        return redirect('admin/manage-social/edit');
    }
}
