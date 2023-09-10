<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Model\User;
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
}
