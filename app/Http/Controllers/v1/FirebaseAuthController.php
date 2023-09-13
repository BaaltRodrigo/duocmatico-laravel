<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Model\User;
use Illuminate\Http\Request;

class FirebaseAuthController extends Controller
{
    /**
     * This route is protected by the auth.firebase middleware
     */
    public function me(Request $request): UserResource
    {
        return new UserResource(auth()->user());
    }
}
