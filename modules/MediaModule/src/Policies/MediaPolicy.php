<?php

namespace Kolette\Media\Policies;

use Kolette\Auth\Enums\Role;
use Kolette\Auth\Models\User;
use Kolette\Media\Enums\MediaCollectionType;
use Kolette\Media\Models\Media;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    public function before(?User $user, $ability): ?bool
    {
        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        return null;
    }


    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Media $media): bool
    {
        if ($media->collection_name === MediaCollectionType::UNASSIGNED) {
            return $user->getKey() === $media->model_id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Media $media): bool
    {
        if ($media->collection_name === MediaCollectionType::UNASSIGNED) {
            return $user->getKey() === $media->model_id;
        }

        return false;
    }
}
