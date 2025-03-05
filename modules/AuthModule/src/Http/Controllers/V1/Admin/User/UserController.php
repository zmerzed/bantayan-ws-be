<?php

namespace Kolette\Auth\Http\Controllers\V1\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kolette\Auth\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedSort;
use App\Actions\Admin\CreateCustomer;
use App\Actions\Admin\UpdateCustomer;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Http\Requests\User\UpdateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Kolette\Auth\Http\Requests\Admin\Customer\CustomerStoreRequest;
use Kolette\Auth\Http\Requests\Admin\Customer\CustomerUpdateRequest;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin']);
        // $this->authorizeResource(User::class);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $users = QueryBuilder::for(User::class)
            //->with('businessInformation')
            ->allowedIncludes(
                'avatar', 
                'businessInformation',
                'businessInformation.avatar',
                'businessInformation.businessHours',
                'selectedRewardSystem',
                'customerInformation'
            )
            ->allowedSorts(
                'first_name', 
                'last_name', 
                'email', 
                'phone_number', 
                'blocked_at', 
                'created_at',
                //AllowedSort::custom('business_information_name', new BusinessNameSort(), 'name'),
            )
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::scope('with_blocked')->ignore(null),
                AllowedFilter::scope('only_blocked')->ignore(null),
                AllowedFilter::scope('merchant')->ignore(null),
                AllowedFilter::scope('customer')->ignore(null),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->perPage());

        return UserResource::collection($users);
    }

    public function show(User $user): UserResource
    {
        $user->load('avatar');

        return new UserResource($user);
    }

    public function store(CustomerStoreRequest $request, CreateCustomer $action)
    {
        //\DB::beginTransaction();
        return DB::transaction(function () use ($request, $action) {
            return $action->execute($request);
        });
    }


    public function update(CustomerUpdateRequest $request, UpdateCustomer $action, User $user)
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
