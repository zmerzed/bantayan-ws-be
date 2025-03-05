<?php

namespace Kolette\Auth\Http\Controllers\V1;

use Kolette\Auth\Actions\SendVerificationCode;
use Kolette\Auth\Enums\Role;
use Kolette\Auth\Enums\UsernameType;
use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Requests\RegisterUserRequest;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function __invoke(RegisterUserRequest $request, SendVerificationCode $sendVerificationCode): JsonResponse
    {

        $user = new User();
        $user->fill($request->safe()->only(['email']));

        $usesEmailAuthentication = $request->has('email') && $request->has('password');

        if ($usesEmailAuthentication) {
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->primary_username = UsernameType::EMAIL;
            $user->email_verified_at = Carbon::now();
        } else {
            $user->phone_number = $request->input('phone_number');
            $user->primary_username = UsernameType::PHONE_NUMBER;
            $user->phone_number_verified_at = Carbon::now();
        }

        $user->save();

        if ($request->filled('role')) {
            $user->syncRoles(
                $request->role ? constant("Kolette\Auth\Enums\Role::$request->role") : Role::USER
            );
        }

        if ($request->input('role') == Role::MERCHANT) {
            $user->createDefaultRewardSystems();
            $user->createDefaultBusinessInformation();
        } else {
            $user->createDefaultCustomerInformation();
        }

        // $sendVerificationCode->execute($user);

        $newAccessToken = $user->createToken($request->header('user-agent', config('app.name')));

        return $this->respondWithToken($newAccessToken->plainTextToken, new UserResource($user->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem')));
    }
}
