<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Chuckbe\Chuckcms\Models\Template;

class LoginController extends Controller
{
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
    protected $redirectTo = '/dashboard';

    public function redirectTo(){
        
        if(Auth::user()->hasRole('customer')){
            return config('chuckcms-module-ecommerce.auth.redirect.customer');
        } 

        if(Auth::user()->hasRole('user')){
            return config('chuckcms-module-ecommerce.auth.redirect.user');
        } 

        if(Auth::user()->hasRole('moderator')){
            return config('chuckcms-module-ecommerce.auth.redirect.moderator');
        } 

        if(Auth::user()->hasRole('administrator')){
            return config('chuckcms-module-ecommerce.auth.redirect.administrator');
        } 

        if(Auth::user()->hasRole('super-admin')){
            return config('chuckcms-module-ecommerce.auth.redirect.super-admin');
        } 
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $templateHintpath = config('chuckcms-module-ecommerce.auth.template.hintpath');
        $template = Template::where('type', 'ecommerce')->where('active', 1)->where('hintpath', $templateHintpath)->first();
        return view('chuckcms-template-london::templates.chuckcms-template-london.account.auth', compact('errors', 'template')); // TODO: make view from config
    }

    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|exists:users,' . $this->username() . ',active,1',
            'password' => 'required',
        ], [
            $this->username() . '.exists' => 'The selected email is invalid or the account is not active.'
        ]);
    }
}
