<?php

namespace Kolette\Auth\Http\Controllers\V1;

use App\Actions\ModifyBusinessInformationAndInformationAction;
use App\Enums\RewardSystem;
use App\Events\OnboardUser;
use App\Http\Requests\Merchant\BusinessHoursRequest;
use App\Rules\UniqueEmailByRole;
use Kolette\Auth\Enums\Role;
use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class OnBoardingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:MERCHANT')->except('email', 'complete');
    }

    /**
     * Set the email of the user.
     */
    public function email(Request $request): UserResource
    {
        Gate::authorize('update-onboarding-details');

        /** @var User $user */
        $user = $request->user();

        $payload = $request->validate([
            'email' => [
                'required',
                new UniqueEmailByRole($user->getDefaultRoleName(), $user->id),
            ],
        ]);

        $user->email = $payload['email'];
        $user->save();

        return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
    }

    public function businessName(Request $request): UserResource
    {
        Gate::authorize('update-onboarding-details');

        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'business_name' => [
                'required',
                'string',
                'unique:business_informations,name'
            ]
        ]);

        $user->businessInformation()->update(['name' => $request->business_name]);

        return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
    }

    public function rewardSystem(Request $request): UserResource
    {
        Gate::authorize('update-onboarding-details');

        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'reward_system' => [
                'required',
                'string',
                Rule::in(RewardSystem::getValues())
            ]
        ]);

        return DB::transaction(function () use ($request, $user) {
            $user->rewardSystems()->update(['selected' => false]);
            $user->rewardSystems()->where('type', $request->reward_system)->first()?->select();

            $user->save();

            return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
        });
    }

    public function redeemAmount(Request $request): UserResource
    {
        Gate::authorize('update-onboarding-details');

        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'redeem_amount' => [
                'required',
                'numeric',
                'gt:0'
            ]
        ]);

        $user->selectedRewardSystem()->update(['redeem_amount' => $request->redeem_amount]);

        return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
    }

    public function businessHours(BusinessHoursRequest $request, ModifyBusinessInformationAndInformationAction $action): UserResource
    {
        Gate::authorize('update-onboarding-details');

        $request->validate([
            'place_id'  => ['required', 'string'],
            'address'   => ['required', 'string', 'max:255'],
        ]);

        return DB::transaction(function () use ($request, $action) {
            /** @var User $user */
            $user = $request->user();

            return $action->execute($request, $user);
        });
    }

    public function businessLogo(Request $request): UserResource
    {
        $request->validate(['avatar' => 'required|image']);

        return DB::transaction(function () use ($request) {
            /** @var User $user */
            $user = $request->user();

            $user->setAvatar($request->file('avatar'));

            return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
        });
    }

    public function avatar(Request $request): UserResource
    {
        $request->validate(['avatar' => 'required|image']);

        return DB::transaction(function () use ($request) {
            /** @var User $user */
            $user = $request->user();

            $user->setAvatar($request->file('avatar'));

            return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
        });
    }

    /**
     * Handles the request in completing the onboarding process.
     */
    public function complete(Request $request): UserResource
    {
        return DB::transaction(function () use ($request) {
            /** @var User $user */
            $user = $request->user();

            $user->onboard();

            if ($user->hasRole(Role::MERCHANT)) {
                $user->payouts_enabled = true;
                $user->save();
            }

            OnboardUser::dispatch($user);

            return UserResource::make($user->fresh()->load('avatar', 'businessInformation.businessHours', 'selectedRewardSystem'));
        });
    }
}
