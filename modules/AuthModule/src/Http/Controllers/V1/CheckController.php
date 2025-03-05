<?php

namespace Kolette\Auth\Http\Controllers\V1;

use Kolette\Auth\Enums\AuthenticationType as AuthType;
use Kolette\Auth\Enums\ErrorCodes;
use Kolette\Auth\Enums\Role;
use Kolette\Auth\Enums\UsernameType;
use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Requests\CheckEmailRequest;
use Kolette\Auth\Http\Requests\CheckUsernameRequest;
use Kolette\Auth\Models\User;
use Kolette\Auth\Support\ValidatesEmail;
use Kolette\Auth\Support\ValidatesPhone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class CheckController extends Controller
{
    use ValidatesEmail;
    use ValidatesPhone;

    /**
     * Check if email exist
     */
    public function checkEmail(CheckEmailRequest $request): JsonResponse
    {
        $data = $request->validated();

        /** @var User $data */
        $user = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', $request->role))
            ->where('email', $data['email'])
            ->withBlocked()
            ->first();

        if (!$user) {
            return $this->respondWithError(ErrorCodes::USERNAME_NOT_FOUND, Response::HTTP_NOT_FOUND);
        }

        if ($user->isBlocked()) {
            return $this->respondWithError(ErrorCodes::ACCOUNT_BLOCKED, Response::HTTP_UNAUTHORIZED);
        }

        // check if user password is set
        if (!$user->hasPassword()) {
            return $this->respondWithError(ErrorCodes::PASSWORD_NOT_SUPPORTED, Response::HTTP_UNAUTHORIZED);
        }

        // check if user email is verified
        // disable for now
        // if (!$user->isEmailVerified()) {
        //     return $this->respondWithError(ErrorCodes::UNVERIFIED_EMAIL, 401);
        // }

        return response()->json([
            'data' => ['email' => $data['email']],
        ]);
    }

    public function checkUsername(CheckUsernameRequest $request): JsonResponse|JsonResource
    {
        $data = $request->validated();

        $user = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', $request->role))
            ->hasUsername($data['username'])
            ->withBlocked()
            ->first();

        $usesEmail = $this->isEmail($data['username']);

        $metadata = [
            'username_type' => $usesEmail ? UsernameType::EMAIL : UsernameType::PHONE_NUMBER,
            'auth_type' => $usesEmail ? AuthType::PASSWORD : AuthType::OTP,
        ];

        /**
         * Check if request is from CMS
         * Prevent users from logging in on the CMS
         */
        if ($user && $request->get('is_admin') && !$user->hasRole(Role::ADMIN)) {
            return $this->respondWithError(
                ErrorCodes::UNAUTHORIZED_ACTION,
                Response::HTTP_UNAUTHORIZED,
                null,
                $metadata
            );
        }

        if (!$user) {
            return $this->respondWithError(
                ErrorCodes::USERNAME_NOT_FOUND,
                Response::HTTP_NOT_FOUND,
                null,
                $metadata
            );
        }

        if ($user->isBlocked()) {
            return $this->respondWithError(
                ErrorCodes::ACCOUNT_BLOCKED,
                Response::HTTP_UNAUTHORIZED,
                null,
                $metadata
            );
        }

        if ($user->isEmailPrimary() && !$usesEmail) {
            return $this->respondWithError(
                ErrorCodes::AUTHENTICATION_EMAIL_REQUIRED,
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($user->isPhonePrimary() && $usesEmail) {
            return $this->respondWithError(
                ErrorCodes::AUTHENTICATION_PHONE_NUMBER_REQUIRED,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $data = array_merge([
            'username' => $data['username'],
            'has_password' => $user->hasPassword(),
        ], $metadata);

        // check if the user uses email to login
        // disable for now
        // if ($usesEmail) {
        // check if email is verified
        // if (!$user->isEmailVerified()) {
        //     return $this->respondWithError(ErrorCodes::UNVERIFIED_EMAIL, 401);
        // }
        // } else {
        // check if phone number is verified
        // if (!$user->isPhoneNumberVerified()) {
        //     return $this->respondWithError(ErrorCodes::UNVERIFIED_PHONE_NUMBER, 401);
        // }
        // }

        return JsonResource::make($data);
    }
}
