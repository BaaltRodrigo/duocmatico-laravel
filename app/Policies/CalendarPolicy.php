<?php

namespace App\Policies;

use App\Models\Calendar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CalendarPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        // 
    }

    public function view(User $user, Calendar $calendar)
    {
        // Check if user owns calendar
        $is_owner = $user->id === $calendar->user_id;
        
        return $is_owner || $calendar->is_public
            ? Response::allow()
            : Response::deny('You are not allowed to view this calendar');
    }

    public function update(User $user, Calendar $calendar)
    {
        return $user->id === $calendar->user_id
            ? Response::allow()
            : Response::deny('You are not allowed to update this calendar');
    }

    public function destroy(User $user, Calendar $calendar)
    {

        return $user->id === $calendar->user_id
            ? Response::allow()
            : Response::deny('You are not allowed to delete this calendar');
    }

    public function create(User $user)
    {
        return Response::allow();
    }
}
