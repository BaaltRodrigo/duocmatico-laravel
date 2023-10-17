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
     */
    public function actingAsFirebaseUser(): void
    {
        $auth = Firebase::auth();
        $email = env('FIREBASE_TEST_USER_EMAIL');
        $password = env('FIREBASE_TEST_USER_PASSWORD');
        $signInResult = $auth->signInWithEmailAndPassword($email, $password); 

        $token = $signInResult->idToken();
        
        $this->withHeader('Authorization', 'Bearer ' . $token);
    }
}