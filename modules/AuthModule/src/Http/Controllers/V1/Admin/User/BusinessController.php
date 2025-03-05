<?php

namespace Kolette\Auth\Http\Controllers\V1\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kolette\Auth\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessInformation;
use App\Actions\Admin\CreateMerchant;
use App\Actions\Admin\UpdateMerchant;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Http\Requests\User\UpdateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Kolette\Auth\Http\Requests\Admin\Business\BusinessStoreRequest;
use Kolette\Auth\Http\Requests\Admin\Business\BusinessUpdateRequest;

class BusinessController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin']);
        // $this->authorizeResource(User::class);
    }

    public function store(BusinessStoreRequest $request, CreateMerchant $action)
    {
        return DB::transaction(function () use ($request, $action) {
            return $action->execute($request);
        });
    }

    public function update(BusinessUpdateRequest $request, User $user, UpdateMerchant $action)
    {
        return DB::transaction(function () use ($request, $action, $user) {
            return $action->execute($request, $user);
        });
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([], Response::HTTP_OK);
    }
}
