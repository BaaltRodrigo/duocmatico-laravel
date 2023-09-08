<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Firebase facade
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseAuthController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $this->auth = Firebase::auth();
    }

    /**
     * This route is protected by the auth.firebase middleware
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user
        ]);
    }

    /**
     * Check if a given token is valid. Not valid token exception should be handled by the global exception handler
     */
    public function checkToken(Request $request)
    {
        $token = $request->bearerToken();
        // token verification
        $verifiedIdToken = $this->auth->verifyIdToken($token);
        // Get user data
        $uid = $verifiedIdToken->claims()->get('sub');
        $user = $this->auth->getUser($uid);

        return response()->json([
            'user' => $user
        ]);
    }
}
