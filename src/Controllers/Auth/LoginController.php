<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers\Auth;

use Auth;
use ChuckCart;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Template;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    public function redirectTo()
    {
        $userId = Auth::user()->id;

        ChuckCart::instance('shopping')->restore('shopping_' . $userId);
        $cart = ChuckCart::instance('shopping')->content();
        ChuckCart::instance('shopping')->store('shopping_' . $userId);

        ChuckCart::instance('wishlist')->restore('wishlist_' . $userId);
        $wishlist = ChuckCart::instance('wishlist')->content();
        ChuckCart::instance('wishlist')->store('wishlist_' . $userId);

        if (Auth::user()->hasRole('customer')) {
            return config('chuckcms-module-ecommerce.auth.redirect.customer');
        }

        if (Auth::user()->hasRole('user')) {
            return config('chuckcms-module-ecommerce.auth.redirect.user');
        }

        if (Auth::user()->hasRole('moderator')) {
            return config('chuckcms-module-ecommerce.auth.redirect.moderator');
        }

        if (Auth::user()->hasRole('administrator')) {
            return config('chuckcms-module-ecommerce.auth.redirect.administrator');
        }

        if (Auth::user()->hasRole('super-admin')) {
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
        $template = Template::where('active', 1)->where('hintpath', $templateHintpath)->first();
        $blade = $templateHintpath . '::templates.' . $template->slug . '.account.auth';

        if (view()->exists($blade)) {
            return view($blade, compact('template'));
        }

        return view('chuckcms::auth.login');
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
