<?php

namespace App\Policies;

use Kolette\Auth\Enums\Permission;
use Kolette\Auth\Enums\Role;
use Kolette\Marketplace\Models\Order;
use Kolette\Auth\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    use HandlesAuthorization;

    public function before(?User $user, $ability): ?bool
    {
        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): Response
    {
        return $order->isOwner($user) || $order->isMerchant($user) ? $this->allow() : $this->deny(__('error_messages.order.owner'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): Response
    {
        return $order->isOwner($user) || $order->isMerchant($user) ? $this->allow() : $this->deny(__('error_messages.order.owner'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): Response
    {
        return $order->isOwner($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): Response
    {
        return $order->isOwner($user) || $order->isMerchant($user) ? $this->allow() : $this->deny(__('error_messages.order.owner'));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): Response
    {
        return $order->isOwner($user) ? $this->allow() : $this->deny(__('error_messages.order.owner'));
    }
}
