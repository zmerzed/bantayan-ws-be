<?php

namespace Kolette\Auth\Http\Controllers\V1\Admin;

use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Models\User;

class UserAccountAccessController extends Controller
{
    /**
     * Disable user
     * Block app access
     */
    public function blockUserAccess(User $user): UserResource
    {
        if (blank($user->blocked_at)) {
            $user->blocked_at = now();
            $user->save();

            $user->tokens()->delete();
        }

        return UserResource::make($user->load('avatar'));
    }

    /**
     * Enable user
     * Grant app access
    */
    public function grantUserAccess(User $user): UserResource
    {
        if (filled($user->blocked_at)) {
            $user->blocked_at = null;
            $user->save();
        }

        return UserResource::make($user->load('avatar'));
    }
}
