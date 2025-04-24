<?php

namespace App\Actions\Admins;

use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Http\Request;
use Kolette\Auth\Enums\Role;
use App\Enums\ApplicationType;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AdminResource;
use App\Http\Requests\Admin\AdminStoreRequest;

class UpdateAdmin
{
    public function execute(AdminStoreRequest|Request $request, Admin $admin): AdminResource
    {
        $admin = DB::transaction(function () use ($request, $admin) {
            $data = array_merge($request->validated(), [
                'password' => $request->password ? bcrypt($request->password) : null
            ]);

            if (is_null($data['password'])) {
                unset($data['password']);
            }

            $admin->update($data);
            $admin->syncRoles(Role::fromKey(strtoupper($request->role))->value);
      
            return $admin;
        });

        return new AdminResource($admin);
    }
}
