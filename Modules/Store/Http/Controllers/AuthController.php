<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Requests\LoginRequest;

use Modules\Admin\Http\Requests\ChangePasswordRequest;
use Modules\Admin\Http\Requests\UpdateProfileRequest;
use App\Repositories\UserRepository;
use Modules\Admin\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use File;



class AuthController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/store/dashboard';
    //protected $guard = 'store';
    private $userRepository;



    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index() {
        $adminDetail = Auth::user();
        return view('store::profile-setting.index',['adminDetail'=>$adminDetail]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(LoginRequest $request) {

        return $this->userRepository->login_store($request);
    }



    public function forgotPassord(Request $request) {
        $post = $request->all();
        try {
            $user = User::where(['email'=> $post['email'],'user_type'=>'store'])->first();
            if (!empty($user)) {
                $reset_password_token = str_random(30);
                $data = [];
                $data['request'] = 'forgot_password';
                $data['link'] = url('store/reset-password/' . $reset_password_token);
                $data['name'] = $user->name;
                $data['email'] = $user->email;
                $data['subject'] ="Reset Password";
                $mail = sendMail($data);
                $user->verify_token = $reset_password_token;
                if ($user->save()) {
                    return Response::json(['success' => true, 'message' =>"Mail sent successfully."]);
                }
                return redirect('/store');
            }else {
                return Response::json(['success' => false, 'message' =>"Email is not Exist."]);
            }
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /*public function resetPassword($token) {
      
        $user = User::where(['user_type'=>'store','verify_token'=>$token])->first();
        if (!empty($user)) {
            $user->verify_token = $token;
            return view('store::auth.reset-password', ['token' => $token]);
        } else{
            \Session::put('link_expired', 'Your Password reset link is expired.');
            return redirect('/admin')->with('error','Your Password reset link is expired.');
        }
    }

    public function reset(ResetPasswordRequest $request) {
        $post = $request->all();
        try {
            $verify_token = $post['verify_token'];
            $password = $post['password'];
            $user = User::where('verify_token', $verify_token)->first();
            if (!empty($user)) {
                $user->verify_token = NULL;
                $user->password = bcrypt($password);
                $user->save();
                return Response::json(['success' => true, 'message' =>"Password reset successfully."]);
            } else {
                return Response::json(['success' => false, 'message' =>"Token expired."]);
            }
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()]);
        }
    }*/


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
   
    public function username() {
        return 'email';
    }

    public function updatePassword(ChangePasswordRequest $request) {
        return $this->userRepository->updatePassword($request);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        Auth::logout();
        \Session::put('log_out', 'Logout successfully!');
        return redirect('store')->with('success', 'Logged-out successfully!');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
   /* protected function guard() {
        return \Auth::guard('store');
    }*/

    /**
     *
     * @param UpdateProfileRequest $request
     * @return type
     */
    public function updateProfile(UpdateProfileRequest $request) {
        return $this->userRepository->updateProfile($request);
    }




}
