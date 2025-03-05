<?php

namespace Kolette\Auth\Http\Controllers\V1;

use Kolette\Auth\Enums\Role;
use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Requests\UpdateProfileRequest;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show current authenticated user profile
     */
    public function index(): UserResource
    {
        return UserResource::make(auth()->user()->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
    }

    /**
     * Update current authenticated user profile
     */
    public function update(UpdateProfileRequest $request): UserResource
    {
        $user = DB::transaction(function () use ($request) {
            /** @var User $user */
            $user = auth()->user();

            if ($user->hasRole(Role::MERCHANT)) {
                $user->businessInformation()->update(['name' => $request->business_name]);
            } else if ($user->hasRole(Role::USER)) {
                $user->update($request->only('first_name', 'last_name'));
            }

            if ($request->has('avatar')) {
                /**
                 * If the avatar parameter value is null,
                 * we will assume that the user was trying to remove the avatar.
                 *
                 * Else it was trying to set a new avatar.
                 */
                $avatarId = $request->input('avatar');
                if (is_null($avatarId)) {
                    $user->removeAvatar();
                } else {
                    $user->setAvatarByMediaId($avatarId);
                }
            }

            return $user;
        });

        return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
    }
}
