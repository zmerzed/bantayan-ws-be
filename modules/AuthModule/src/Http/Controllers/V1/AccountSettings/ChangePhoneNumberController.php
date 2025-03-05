<?php

namespace Kolette\Auth\Http\Controllers\V1\AccountSettings;

use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Models\ChangeRequest;
use Kolette\Auth\Models\User;
use Kolette\Auth\Support\CodeGenerator;
use Kolette\Sms\Facades\Sms;
use Kolette\Sms\SmsMessage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Kolette\Auth\Http\Requests\AccountSettings\ChangePhoneNumberRequest;
use Kolette\Auth\Http\Requests\AccountSettings\ChangeVerificationRequest;

class ChangePhoneNumberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verification-token');
    }

    /**
     * When users send a request to change his email, a verification code
     * will be sent and will be used to verify before he/she can update
     * his/her old email.
     */
    public function change(ChangePhoneNumberRequest $request): UserResource
    {
        /** @var User $user */
        $user = $request->user();

        $newPhoneNumber = $request->input('phone_number');

        if ($user->isPhonePrimary()) {
            rescue(function () use ($user, $newPhoneNumber) {
                $code = CodeGenerator::make();

                $user->changeRequestFor('phone_number', $newPhoneNumber, $code);
                // Send verification code
                $content = Lang::get('Your phone number verification code is :code', ['code' => $code]);

                Sms::send($newPhoneNumber, new SmsMessage($content));
            });
        } else {
            $user->phone_number = $newPhoneNumber;
            $user->save();
        }

        return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
    }

    /**
     * Once the token was verified and valid, the changes will be applied.
     */
    public function verify(ChangeVerificationRequest $request): UserResource
    {
        /** @var User $request */
        $user = $request->user();

        /** @var ChangeRequest $changeRequest */
        $changeRequest = $user->getChangeRequestFor('phone_number');

        if (is_null($changeRequest)) {
            abort(Response::HTTP_NOT_FOUND, 'No request for change phone number.');
        }

        if (!$changeRequest->isTokenValid($request->input('token'))) {
            abort(Response::HTTP_BAD_REQUEST, 'The verification code was invalid.');
        }

        $user->applyChangeRequest($changeRequest);
        $user->verifyPhoneNumberNow();

        return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
    }
}
