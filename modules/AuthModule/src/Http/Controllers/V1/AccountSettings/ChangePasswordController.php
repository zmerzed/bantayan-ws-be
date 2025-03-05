<?php

namespace Kolette\Auth\Http\Controllers\V1\AccountSettings;

use Kolette\Auth\Enums\ErrorCodes;
use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Kolette\Auth\Http\Requests\AccountSettings\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(ChangePasswordRequest $request): JsonResponse|UserResource
    {
        $user = $request->user();

        if ($request->input('old_password') === $request->input('new_password')) {
            return $this->respondWithError(ErrorCodes::USING_OLD_PASSWORD, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return $this->respondWithError(ErrorCodes::INVALID_CREDENTIALS, Response::HTTP_UNAUTHORIZED);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
    }
}
