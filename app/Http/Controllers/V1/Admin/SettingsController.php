<?php

namespace App\Http\Controllers\V1\Admin;

use Illuminate\Http\Request;
use Kolette\Auth\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsController extends Controller
{
    /**
     * Display settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = collect([
            'roles' => Role::getAdminRoles()
        ]);
        return JsonResource::make($settings);
    }
}
