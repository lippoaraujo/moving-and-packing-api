<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class ApiProtectedRoute extends BaseMiddleware
{
    use ApiResponser;

    private const HTTP_CODE = Response::HTTP_UNAUTHORIZED;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $message = "Token is Invalid";
                return $this->errorResponse($message, self::HTTP_CODE);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $message = "Token is Expired";
                return $this->errorResponse($message, self::HTTP_CODE);
            } else {
                $message = "Authorization Token not found";
                return $this->errorResponse($message, self::HTTP_CODE);
            }
        }

        return $next($request);
    }
}
