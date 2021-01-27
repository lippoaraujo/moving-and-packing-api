<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

trait ApiResponser
{
    /**
     * Build succes response
     * @param string|array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, int $code = Response::HTTP_OK)
    {
        return response()->json(['data' => $data], $code);
    }

    /**
     * Build error response
     * @param string|array $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($message, int $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json(['message' => $message, 'code' => $code], $code);
    }

    /**
     * Build token response
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token, $allPermissions = null)
    {

        $json = [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL()
        ];

        $json = collect($json);

        if (!empty($allPermissions)) {
            $json = $json->merge($allPermissions);
        }

        return response()->json($json, Response::HTTP_OK);
    }
}
