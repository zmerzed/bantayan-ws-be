<?php

namespace App\Http\Controllers\V1\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use Kolette\Auth\Enums\Role;
use App\Actions\Admins\StoreAdmin;
use Illuminate\Support\Facades\DB;
use App\Actions\Admins\UpdateAdmin;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Filters\Filter;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = QueryBuilder::for(Admin::class)
            ->withRoles([Role::ADMIN, Role::READER])
            ->allowedFilters([
                'full_name',
                'email',
                'search',
                AllowedFilter::callback('role', function ($query, $value) {
                    $query->whereHas('roles', function ($q) use ($value) {
                        $q->where('name', $value);
                    });
                }),
            ])
            ->defaultSort('-created_at')
            ->getOrPaginate();

        return AdminResource::collection($collection);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminStoreRequest $request)
    {
    
        return (new StoreAdmin)->execute($request);
    }


    /**
     * Show
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return new AdminResource($admin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUpdateRequest $request, Admin $admin)
    {
        return (new UpdateAdmin)->execute($request, $admin);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response()->noContent();
    }
}
