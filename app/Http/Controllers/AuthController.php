<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function loginPage()
    {
        return view('login');
    }

    public function firebaseLogin(Request $request)
    {
        $leewayInSeconds = 360; // 5 minutes
        $idToken = $request->token;
        try {

            $verifiedIdToken = $this->auth->verifyIdToken(
                $idToken,
                false,              // checkIfRevoked
                $leewayInSeconds    // leeway
            );

            $uid = $verifiedIdToken->claims()->get('sub');

            $user = $this->auth->getUser($uid);

            Session::put('firebase_user', [
                'uid' => $uid,
                'email' => $user->email,
                'name' => $user->displayName
            ]);
            
            return response()->json([
                'status' => 'success'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 401);

        }
    }

    public function logout()
    {
        Session::forget('firebase_user');

        return redirect('/login');
    }
}
