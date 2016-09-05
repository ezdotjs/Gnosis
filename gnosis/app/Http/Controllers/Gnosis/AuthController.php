<?php

namespace App\Http\Controllers\Gnosis;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Gnosis\User;
use App\Http\Requests\Gnosis\LoginRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Session;

class AuthController extends Controller
{
    use ThrottlesLogins;

    public function getLogin()
    {
        return view('gnosis/layouts/login');
    }

    public function postLogin(LoginRequest $request)
    {
        if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            Session::flash('flash_message', [
                'type'    => 'danger',
                'message' => 'Too many failed login attempts, please contact an administrator'
            ]);
            return $this->sendLockoutResponse($request);
        }

        $credentials = $request->only([
            'email',
            'password'
        ]);

        if ($x = $this->guard()->attempt($credentials, false)) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            return redirect()->intended(route('dashboard'));
        }

        if (!$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        Session::flash('flash_message', [
            'type'    => 'danger',
            'message' => 'There was a problem logging in'
        ]);

        return redirect()
            ->back()
            ->withInput($request->only([
                $this->username()
            ]));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('login.get');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
