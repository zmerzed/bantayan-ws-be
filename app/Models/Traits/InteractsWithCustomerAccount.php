<?php

namespace App\Models\Traits;

use App\Enums\PointTransactionStatus;
use App\Enums\PointTransactionTypes;
use App\Enums\RewardSystem as EnumsRewardSystem;
use App\Models\PointTransaction;
use App\Models\RewardSystem;
use Kolette\Auth\Models\User;
use Kolette\Marketplace\Models\Order;
use Kolette\Marketplace\Models\ProductOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait InteractsWithCustomerAccount
{
    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function rewardSystems(): HasMany
    {
        return $this->hasMany(RewardSystem::class);
    }

    public function selectedRewardSystem(): HasOne
    {
        return $this->hasOne(RewardSystem::class)->where('selected', true);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function syncDetails(): void
    {
        /** @var \Kolette\Auth\Models\User $this */
        foreach (EnumsRewardSystem::getValues() as $rewardSystem) {
            $this->rewardSystems()->firstOrCreate([
                'type' => $rewardSystem,
            ]);
        }
    }

    public function updateRewardSystemRedeemAmount(string $rewardSystem, float $amount): void
    {
        /** @var \Kolette\Auth\Models\User $this */
        $this->rewardSystems()
            ->where(['type' => $rewardSystem])
            ->first()
            ->update(['redeem_amount' => $amount]);
    }

    public function resetPoints(User $merchant)
    {
        $points = $this->pointTransactions()->active()->get();
        $points->each(function (PointTransaction $point) use ($merchant) {
            $point->where('seller_id', $merchant->id)
                ->update(['status' => PointTransactionStatus::INACTIVE]);
        });
    }

    public function getPoints(User $merchant)
    {
        /** @var PointTransaction */
        $lastTransaction = $this->pointTransactions()
            ->where('seller_id', $merchant->id)
            ->where('reward_system_id', $merchant->selectedRewardSystem->id)
            ->latest()
            ->active()
            ->first();

        return $lastTransaction?->end_balance ?? 0;
    }

    public function getFormattedPoints(User $merchant)
    {
        /** @var RewardSystem*/
        $rewardSystem = $merchant->selectedRewardSystem;
        $points = $this->getPoints($merchant);

        return "$points/{$rewardSystem->redeem_amount}";
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeWithHasPoints(Builder $query, User $user)
    {
        $query->addSelect([
            'has_points' => (PointTransaction::query())
                ->selectRaw('count(id) as has_points')
                ->where('user_id', $user->getKey())
                ->whereColumn('users.id', 'point_transactions.seller_id')
                ->where('points', '>', 0)
                ->active()
                ->take(1),
        ]);

        $query->withCasts([
            'has_points' => 'integer'
        ]);
    }
}
