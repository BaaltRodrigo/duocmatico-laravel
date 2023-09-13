<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AcademicChargePolicy
{

    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        // Index should be a public route
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): Response
    {
        // Show is a public route
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo('academic-charges.create')
            ? Response::allow()
            : Response::deny('You do not have permission to create an academic charges.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): Response
    {
        return $user->hasPermissionTo('academic-charges.update')
            ? Response::allow()
            : Response::deny('You do not have permission to update an academic charges.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        return $user->hasPermissionTo('academic-charges.destroy')
            ? Response::allow()
            : Response::deny('You do not have permission to delete an academic charges.');
    }
}
