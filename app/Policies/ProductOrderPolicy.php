<?php

namespace App\Policies;

use Kolette\Auth\Enums\Permission;
use Kolette\Auth\Enums\Role;
use Kolette\Auth\Models\User;
use Kolette\Marketplace\Models\ProductOrder;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ProductOrder $product): bool
    {
        return true;
    }

    /**
     * @throws \Throwable
     */
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ProductOrder $product): Response
    {
        return $product->isOwner($user) ? $this->allow() : $this->deny(__('error_messages.cart.owner'));
    }

    public function delete(User $user, ProductOrder $product): Response
    {
        return $product->isOwner($user) ? $this->allow() : $this->deny(__('error_messages.cart.owner'));
    }

    public function restore(User $user, ProductOrder $product): Response
    {
        return $product->isOwner($user) ? $this->allow() : $this->deny(__('error_messages.cart.owner'));
    }

    public function forceDelete(User $user, ProductOrder $product): Response
    {
        return $product->isOwner($user) ? $this->allow() : $this->deny(__('error_messages.cart.owner'));
    }
}
