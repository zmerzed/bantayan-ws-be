<?php

namespace App\Actions\Admins;

use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Enums\ApplicationType;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AdminResource;
use App\Http\Requests\Admin\AdminStoreRequest;
use Spatie\Permission\Models\Role;

class StoreAdmin
{
    public function execute(AdminStoreRequest|Request $request): AdminResource
    {
        $admin = DB::transaction(function () use ($request) {
            $data = array_merge($request->validated(), [
                'password' => bcrypt($request->password)
            ]);

            $admin = Admin::create($data);
            $role = Role::whereName($request->role)->first();
            $admin->assignRole($role);
            return $admin;
        });

        return new AdminResource($admin);
    }
}
