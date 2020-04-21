<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Request\ForgotPasswordRequest;
use App\Http\Request\ResetPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // public function forgotPassord(ForgotPasswordRequest $request) {
    //     $post = $request->all();
  
    //     try {
    //         $user = User::where(['email'=> $post['email']])->first();
    //         if (!empty($user)) {
    //             $reset_password_token = str_random(30);
    //             $data = [];
    //             $data['request'] = 'forgot_password';
    //             $data['link'] = url('reset-password/' . $reset_password_token);
    //             $data['name'] = $user->name;
    //             $data['email'] = $user->email;
    //             $data['subject'] ="Reset Password";
    //             $mail = sendMail($data);  
    //             if(!$mail){
    //                 return Response::json(['success' => false, 'message' =>"Something went wrong."]); 
    //             }
                
    //             if (!empty($user)) {
    //                 $user->verify_token = $reset_password_token;
    //                 if ($user->save()) {
    //                     return Response::json(['success' => true, 'message' =>"Mail sent successfully."]);
    //                 }
    //             } 
    //             return redirect('/');
    //         }else {
    //             return Response::json(['success' => false, 'message' =>"Email is not Exist."]);
    //         }
    //     } catch (\Exception $e) {
    //         return Response::json(['success' => false, 'message' => $e->getMessage()]);
    //     }
    // }

    // public function resetPassword($token) {
    //     $user = User::where(['verify_token'=>$token])->first();
    //     if (!empty($user)) {
    //         $user->verify_token = $token;
    //         return view('auth.reset-password', ['token' => $token]);
    //     } else{            
    //         return redirect('/')->with('error','Token expired.');
    //     }              
    // }

    // public function reset(ResetPasswordRequest $request) {
    //     $post = $request->all();
    //     try {
    //         $verify_token = $post['verify_token'];
    //         $password = $post['password'];
    //         $user = User::where('verify_token', $verify_token)->first();
    //         if (!empty($user)) {
    //             $user->verify_token = NULL;
    //             $user->password = bcrypt($password);
    //             $user->save();
    //             return Response::json(['success' => true, 'message' =>"Password reset successfully."]);
    //         } else {
    //             return Response::json(['success' => false, 'message' =>"Toekn expired."]);
    //         }
    //     } catch (\Exception $e) {
    //         return Response::json(['success' => false, 'message' => $e->getMessage()]);
    //     }
    // }
}
