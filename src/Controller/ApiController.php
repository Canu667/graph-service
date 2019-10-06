<?php
declare(strict_types=1);


namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="My Graph API", version="1.0.0")
 */
class ApiController
{
    public function openApiJson(): JsonResponse
    {
        $openapi = \OpenApi\scan(__DIR__. '/..');
        $json = $openapi->toJson();

        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }
}
