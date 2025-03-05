<?php

namespace Kolette\Auth\Http\Controllers\V1;

use Kolette\Auth\Enums\Role;
use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Requests\StoreAvatarRequest;
use Kolette\Auth\Models\User;
use Kolette\Media\Http\Resources\MediaResource;

class ProfileAvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store new user avatar
     */
    public function store(StoreAvatarRequest $request): MediaResource
    {
        $data = $request->validated();

        /** @var User $user */
        $user = auth()->user();

        $avatar = $user->setAvatar($data['avatar']);

        return new MediaResource($avatar);
    }
}
