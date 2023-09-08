<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseAuth
{

    protected $auth;

    public function __construct()
    {
        $this->auth = Firebase::auth();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for the token in the header
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['status' => 'error','message' => 'Token not provided'], 403);
        }

        // Verify the token
        $verifiedIdToken = $this->auth->verifyIdToken($token, $checkIfRevoked = true);
        
        // Set the user in the request and proceed
        $uid = $verifiedIdToken->claims()->get('sub');
        $userPayload = $this->auth->getUser($uid);
        
        // Check if the user exists on the tables
        // TODO: Move this into an action or something
        $user = User::firstOrCreate([
            'id' => $userPayload->uid,
            'name' => $userPayload->displayName,
            'email' => $userPayload->email,
        ]);

        $request->user = $user;

        return $next($request);
    }
}
