<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public $successStatus = 200;
    public $message = null;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();

            $responseData['error'] = false;
            $responseData['status'] = $this->successStatus;
            $responseData['resMsg'] = "You have logged in successfully.";
            $responseData['user'] = $user;
            $responseData['token'] = $user->createToken('MyApp')->accessToken;

            return response()->json($responseData);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {

        $loggingOut = $request->user()->token()->revoke();

        $responseData['error'] = false;
        $responseData['status'] = $this->successStatus;
        $responseData['resMsg'] = "Successfully logged out.";

        return response()->json($responseData);
        // return response($responseData, 200);
    }
}
