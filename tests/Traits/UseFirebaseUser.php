<?php

namespace Tests\Traits;

use App\Models\User;
use Kreait\Laravel\Firebase\Facades\Firebase;

trait UseFirebaseUser
{

    /**
     * This credentials are the ones from the firebase auth tool.
     * In case that there is a change in the firebase auth tool,
     * this credentials should be updated.
     */
    public string $user_id = '4qVRFaULHRbXnESK7y0xoHKf02Q2';
    public string $user_name = 'Test-Duocmatico';
    public string $user_email = 'duocmatico-admin@example.net';
    public string $user_password = 'securePassword';

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
        return User::create(['id' => $this->user_id,]);
    }
    
    /**
     * Always acts as the default user signed inside firebase auth
     */
    public function actingAsFirebaseUser(): void
    {
        $auth = Firebase::auth();
        $signInResult = $auth->signInWithEmailAndPassword($this->user_email, $this->user_password); 

        $token = $signInResult->idToken();
        
        $this->withHeader('Authorization', 'Bearer ' . $token);
    }
}