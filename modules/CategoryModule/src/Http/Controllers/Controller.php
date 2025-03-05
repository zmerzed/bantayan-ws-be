<?php

namespace Kolette\Category\Http\Controllers;

use App\Support\Response\ReturnsResponse;
use Illuminate\Http\JsonResponse;

/**
 * @method JsonResponse respondWithToken(string $token, mixed $user, int $statusCode = 200)
 * @method JsonResponse respondWithError(string $errorCode, int $statusCode = 400, ?string $message = null, array $metadata = [])
 * @method JsonResponse respondWithEmptyData(int $statusCode = 200)
 */
abstract class Controller extends \Illuminate\Routing\Controller
{
    use ReturnsResponse;
}
