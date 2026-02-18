<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Methods\ReCaptchaValidation;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->activeTheme = active_theme();
    }

    /**
     * Where to redirect users after login.
     * @return string
     */
    public function redirectTo() {
        if (request()->user()->user_type == 'admin') {
            return admin_url().'/dashboard';
        } else {
            return RouteServiceProvider::USER;
        }
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view($this->activeTheme.'auth.login');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ] + validate_recaptcha());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (request()->user()->status == 0) {
            Auth::logout();
            quick_alert_error(___('Your account is blocked'));
            return redirect()->route('login');
        }
        update_user_logs($user);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $admin_user_id = session()->get('quick_admin_user_id');

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        /* Check if admin was logged in as user */
        if($admin_user_id){
            if($admin = User::find($admin_user_id)){
                Auth::login($admin);
                return redirect(route('admin.users.index'));
            }
        }

        if(request()->get('redirected_to')) {
            return redirect(request()->get('redirected_to'));
        }

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
