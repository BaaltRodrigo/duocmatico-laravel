<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Firebase\Auth\Token\Exception\IssuedInTheFuture;

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
            return response()->json(['status' => 'error','message' => 'Token not provided'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            // Verify the token
            $verifiedIdToken = $this->auth->verifyIdToken($token, $checkIfRevoked = true);
        } catch (IssuedInTheFuture $e) {
            // This solves the issue for now, but we should check the time
            // on the server and the time on the token
            $verifiedIdToken = $e->getToken();
        }
        
        // Set the user in the request and proceed
        $uid = $verifiedIdToken->claims()->get('sub');
        $userPayload = $this->auth->getUser($uid);
        
        // Check if the user exists on the tables
        $user = User::find($userPayload->uid);

        if (!$user) {
            // No user, create one
            // TODO: Move this into an action or something
            $user = User::create([
                'id' => $userPayload->uid,
                'name' => $userPayload->displayName ?? $userPayload->email,
                'email' => $userPayload->email,
            ]);
        }

        // At this point, we get an old user or the new one
        // sign the user in
        auth()->login($user);     

        return $next($request);
    }
}
