<?php

namespace Kolette\Auth\Http\Controllers\V1\Admin\Auth;

use App\Models\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kolette\Auth\Models\User;
use App\Http\Controllers\Controller;
use Kolette\Auth\Enums\ErrorCodes;
use Illuminate\Support\Facades\Hash;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Http\Resources\AdminResource;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => [
                'login', 
                'bypassLogin',
            ]
        ]);
    }

    /**
     * Admin User Login
     * @param LoginRequest
     */
    public function login(Request $request)
    {

        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['bail', 'required'],
        ]);
        
        $admin = Admin::where('email', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return $this->respondWithError(ErrorCodes::INVALID_CREDENTIALS, Response::HTTP_UNAUTHORIZED);
        }

        $token = $admin->createToken($request->header('user-agent'))->plainTextToken;

        return $this->respondWithToken($token, new AdminResource($admin));
    }

    /**
     * Bypass User Login
     * @param LoginRequest
     */
    public function bypassLogin(Request $request)
    {
        if (app()->environment('local', 'staging', 'development')) {

            $user = $request->id ? User::find($request->id) : User::where('email', $request->username)->first();

            if (!$user) {
                return $this->respondWithError(ErrorCodes::INVALID_CREDENTIALS, Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken($request->header('user-agent'))->plainTextToken;

            return $this->respondWithToken($token, new UserResource($user));
        }
    }
}
