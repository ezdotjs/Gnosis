<?php

namespace App\Http\Controllers\Gnosis;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use App\Models\Gnosis\User;
use App\Http\Requests\Gnosis\LoginRequest;
use App\Http\Requests\Gnosis\ForgottenRequest;
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

        if ($this->guard()->attempt($credentials, false)) {
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

    public function getForgotten()
    {
        return view('gnosis/layouts/forgotten');
    }

    public function postForgotten(ForgottenRequest $request)
    {
        $user = User::whereEmail($request->get('email'))->first();

        if ($user) {
            $response = $this->broker()->sendResetLink(
                $request->only('email'),
                $this->sendResetLinkEmail($user)
            );
        }

        Session::flash('flash_message', [
            'type'    => 'success',
            'message' => 'An email will been sent to the specified address, providing it exists'
        ]);

        return redirect()->back();
    }

    public function getReset(Request $request)
    {
        return view('gnosis/layouts/reset')->with(['token' => $request->token]);
    }

    public function postReset(Request $request)
    {
        $response = $this->broker()->reset(
            $request->only([
                'email',
                'password',
                'password_confirmation',
                'token'
            ]),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => bcrypt($password),
                    'remember_token' => Str::random(60),
                ])->save();
                $this->guard()->login($user);
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            Session::flash('flash_message', [
                'type'    => 'success',
                'message' => 'Your password was successfully reset. You have been automatically logged in'
            ]);
            return redirect()->route('dashboard');
        }

        Session::flash('flash_message', [
            'type'    => 'danger',
            'message' => 'Your password could not be reset, please try again'
        ]);

        return redirect()->back()->withInput($request->only('email'));
    }

    private function sendResetLinkEmail()
    {
        //
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

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}
