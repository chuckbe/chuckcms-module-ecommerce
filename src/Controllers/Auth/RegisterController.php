<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers\Auth;

use Chuckbe\ChuckcmsModuleEcommerce\Models\User;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Customer;

use Chuckbe\Chuckcms\Chuck\UserRepository;

use App\Http\Controllers\Controller;

use Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Chuckbe\Chuckcms\Models\Template;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
     * User Repository.
     *
     * @var string
     */
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $templateHintpath = config('chuckcms-module-ecommerce.auth.template.hintpath');
        $template = Template::where('type', 'ecommerce')->where('active', 1)->where('hintpath', $templateHintpath)->first();
        return view('chuckcms-template-london::templates.chuckcms-template-london.account.auth', compact('errors', 'template'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'tel' => 'nullable|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['surname'] . ' ' . $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'token' => $this->userRepository->createToken(),
            'active' => 1,
        ]);

        $user->assignRole('customer');

        $json = [];

        $customer = $user->customer()->create([
            'surname' => $data['surname'],
            'name' => $data['name'],
            'tel' => $data['tel'],
            'json' => $json
        ]);

        return $user;
    }
}
