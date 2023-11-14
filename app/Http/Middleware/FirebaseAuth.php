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
     * This middleware has
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $mode strict|optional If the auth methods is optional, the user is not required to be authenticated
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $mode = 'strict'): Response
    {
        // Check for the token in the header
        $token = $request->bearerToken();

        // If the auth is optional, the user is not required to be authenticated
        $is_optional = $mode === 'optional';

        if (!$token) {
            return $is_optional
                ? $next($request) 
                : response()->json(['status' => 'error','message' => 'Token not provided'], Response::HTTP_UNAUTHORIZED);
        }
        
        // From here, the token is present and we can proceed to verify it
        // Verify the token as the route is without optional route
        $verifiedIdToken = $this->auth->verifyIdToken(
            $token,
            true, // Check if the token is revoked
            3600 // Handle 1 hour of delay from the client
        );
        
        // Set the user in the request and proceed
        $uid = $verifiedIdToken->claims()->get('sub');
        $userPayload = $this->auth->getUser($uid);
        
        // Check if the user exists on the tables
        $user = User::firstOrCreate([ 'id' => $userPayload->uid]);

        if ($user->wasRecentlyCreated) {
            $user->assignRole('common'); // default role for every user
        }

        // At this point, we get an old user or the new one
        // sign the user in
        auth()->login($user);     

        return $next($request);
    }
}
