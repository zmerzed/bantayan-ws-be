<?php

namespace Kolette\Auth\Http\Resources;

use Kolette\Auth\Enums\Role;
use App\Models\BusinessInformation;
use App\Http\Resources\BusinessInfoResource;
use App\Http\Resources\RewardSystemResource;
use Kolette\Marketplace\Http\Resources\OrderResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Kolette\Media\Http\Resources\MediaResource;
use App\Http\Resources\CustomerInformationResource;
use Kolette\Marketplace\Http\Resources\ProductResource;

class UserResource extends JsonResource
{
    /**
     * @var \Kolette\Auth\Models\User
     */
    public $resource;

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'blocked_at' => $this->blocked_at,
            'onboarded_at' => $this->onboarded_at,
            'primary_username' => $this->primary_username,
            $this->mergeWhen($this->hasRole(Role::USER), [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'full_name' => "$this->first_name $this->last_name",
                'member_id' => $this->member_id,
                'member_id_dashed' => wordwrap($this->member_id, 4, '-', true),
            ]),

            $this->mergeWhen($this->hasRole(Role::USER), [
                'customer_information' => CustomerInformationResource::make($this->customerInformation)
            ]),

            $this->mergeWhen($this->hasRole(Role::MERCHANT), [
                'lock_reward_system' => $this->lock_reward_system,
                'business_name' => $this->whenLoaded('businessInformation', fn () => $this->businessInformation->name),
                'business_information' => BusinessInfoResource::make($this->whenLoaded('businessInformation')),
                'reward_system' => RewardSystemResource::make($this->whenLoaded('selectedRewardSystem'))
            ]),

            // Computed attributes
            'full_name' => $this->resource->full_name,
            'email_verified' => $this->resource->isEmailVerified(),
            'phone_number_verified' => $this->resource->isPhoneNumberVerified(),
            'verified' => $this->resource->isVerified(),
            'avatar_permanent_url' => route(
                'auth.user.avatar.show',
                ['id' => $this->id, 'timestamp' => strval(optional($this->updated_at)->timestamp)]
            ),
            'avatar_permanent_thumb_url' => route(
                'auth.user.avatar.showThumb',
                ['id' => $this->id, 'timestamp' => strval(optional($this->updated_at)->timestamp)]
            ),
            'mine' => $this->id == optional(auth()->user())->id,
            'role' => $this->getDefaultRoleName(),
            'guest_uid' => $this->whenNotNull($this->guest_uid),

            // Relationship
            'avatar' => MediaResource::make($this->whenLoaded('avatar')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'active_orders' => OrderResource::collection($this->whenLoaded('upcomingOrders'))
        ];
    }
}
