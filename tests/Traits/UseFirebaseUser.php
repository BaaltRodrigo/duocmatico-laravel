<?php

namespace Tests\Traits;

use App\Models\User;
use Kreait\Laravel\Firebase\Facades\Firebase;

trait UseFirebaseUser
{
    /**
     * Creates the default test user from firebase auth, but
     * changes the roles to the ones provided in the array.
     */
    public function createUserWithRoles(Array $roles = null): User
    {
        $user = $this->createUser();

        if ($roles) {
            $user->syncRoles($roles);
        }

        return $user;
    }

    public function createUser(): User
    {
        return User::firstOrCreate(['id' => env('FIREBASE_TEST_USER_UID')]);
    }
    
    /**
     * Always acts as the default user signed inside firebase auth
     * 
     * This sets the Bearer token with a custom id token.
     */
    public function actingAsFirebaseUser(User $user): void
    {
        $auth = Firebase::auth();

        // As passwords has limited attempts to be checked, we created a custom token
        $customToken = $auth->createCustomToken($user->id);
        $signInResults = $auth->signInWithCustomToken($customToken->toString());
        $token = $signInResults->idToken();;
        
        $this->withHeader('Authorization', 'Bearer ' .  $token);
    }
}