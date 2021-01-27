<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\System\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @group Auth
 *
 * APIs for managing Authentication
 */
class AuthController extends Controller
{
    use ApiResponser;

    protected $userService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->userService = $userService;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @bodyParam email email email of the user. Example: jhon@gmail.com
     * @bodyParam password string password of the user. Example: 123
     * @unauthenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $allPermissions = $this->userService->permission();

        return $this->respondWithToken($token, $allPermissions);
    }

    /**
     * Get the authenticated User.
     *
     * @authenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return $this->successResponse(auth('api')->user()->load('roles', 'tenant'));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @authenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return $this->successResponse(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @authenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }
}
