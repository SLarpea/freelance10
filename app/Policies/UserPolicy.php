<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class UserPolicy
{
    use HandlesAuthorization;

    public function modifyRecord(User $user, $creatorId)
    {
        return ($user->hasAnyRole(['Superadmin', 'Administrator']) || $user->id === $creatorId) ? Response::allow() : Response::denyWithStatus(403);
    }
}
