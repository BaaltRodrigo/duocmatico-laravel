<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use Kreait\Laravel\Firebase\Facades\Firebase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function actingAsFirebaseUser(String $email, String $plainPassword)
    {
        $auth = Firebase::auth();
        $signInResult = $auth->signInWithEmailAndPassword($email, $plainPassword); 

        $token = $signInResult->idToken();
        
        $this->withHeader('Authorization', 'Bearer ' . $token);
    }
}
