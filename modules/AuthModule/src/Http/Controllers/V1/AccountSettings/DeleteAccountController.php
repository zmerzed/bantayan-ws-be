<?php

namespace Kolette\Auth\Http\Controllers\V1\AccountSettings;

use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteAccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verification-token');
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->tokens()->delete();

        $user->delete();

        return response()->json([], Response::HTTP_OK);
    }
}
