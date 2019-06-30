<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Input;
use App\Models\User;
use App\Common\CommonUtils as cu;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    use ResetsPasswords;
    public $redirectTo = '/reshall';
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function getResets(){
    	return view('auth.password');
    }
    
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postResets()
    {
        echo 'fffffffffffffffffffffffff';
        die();
    	$account = trim(Input::get('email'));
    	$isEmail = is_numeric($account);
    	if($isEmail){
			$user = User::where('mobile',$account)->first();
    	}else{
    		$user = User::where('email',$account)->first();
    	}
    	if(empty($user)){
    		$return = cu::ajaxReturn(-1,'没有该账户');
    		echo $return;
    		exit;
    	}
    	$pass = trim(Input::get('password'));
    	$passConfirm = trim(Input::get('password_confirmation'));
    	if($pass != $passConfirm){
    		$return = cu::ajaxReturn(-1,'两次输入的密码不一致');
    		echo $return;
    		exit;
    	}
    	$user->password = bcrypt($pass);
    	$user->save();
    	$return = cu::ajaxReturn(1,'',array('redirectUrl'=>'/auth/login'));
    	echo $return;
//     	return redirect('password/reset/'.$token);
    }
}
