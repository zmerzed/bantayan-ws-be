<?php

namespace Kolette\Auth\Http\Controllers\V1;

use Illuminate\Http\Request;
use Kolette\Auth\Enums\Role;
use Kolette\Auth\Models\User;
use Kolette\Auth\Support\ValidatesEmail;
use Kolette\Auth\Support\ValidatesPhone;
use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Http\Resources\NewAccessTokenResponse;

class GuestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Guest Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the authenticating guest user
    |
    */

    public function login(Request $request)
    {
        /** @var User */
        $user = User::query()
            ->where('guest_uid', $request->input('guest_uid'))
            ->first();

        if (!$user) {
            $user = $this->addNewGuestUser($request->input('guest_uid'));
        }

        $newAccessToken = $user->createToken($request->header('user-agent', config('app.name')));

        return $this->respondWithToken($newAccessToken->plainTextToken, UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem')));
    }

    private function addNewGuestUser(string $guestUid): User
    {
        $user = new User();
        $user->first_name = 'GUEST';
        $user->last_name = 'USER';
        $user->guest_uid = $guestUid;
        $user->save();

        $user->onboard();
        $user->syncRoles(Role::GUEST);
        $user->createDefaultCustomerInformation();
        
        return $user;
    }
}
