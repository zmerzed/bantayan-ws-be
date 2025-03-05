<?php

namespace Kolette\Auth\Http\Controllers\V1;

use Kolette\Auth\Http\Controllers\Controller;
use Kolette\Auth\Http\Requests\GenerateOTPRequest;
use Kolette\Auth\Support\OneTimePassword\InteractsWithOneTimePassword;
use Kolette\Auth\Support\ValidatesPhone;
use Illuminate\Http\JsonResponse;

class OneTimePasswordController extends Controller
{
    use InteractsWithOneTimePassword;
    use ValidatesPhone;

    /**
     * Handle the incoming request.
     */
    public function generate(GenerateOTPRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $this->sendOneTimePassword($this->uncleanPhoneNumber($payload['phone_number']));

        return $this->respondWithEmptyData();
    }
}
