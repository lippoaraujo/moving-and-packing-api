<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\System\Entities\User;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected const ID_NOT_VALID = "9999999999";

    /**
     * Return request headers needed to interact with the API.
     *
     * @return Array array of headers.
     */
    protected function headers($user = null)
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];


        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = "Bearer {$token}";
        }

        return $headers;
    }

    protected function getAdminCredentials()
    {
        $email = config('api.apiEmail');
        $password = config('api.apiPassword');

        return [
            'email'    => $email,
            'password' => $password
        ];
    }

    protected function getUserAdmin()
    {
        $cred = $this->getAdminCredentials();
        return User::where('email', $cred['email'])->first();
    }

    protected function getRouteId(string $route, $id = null)
    {
        if(is_null($id)) {
            $route = "{$route}/{$this->getInvalidId()}";
        } else {
            $route = "{$route}/{$id}";
        }

        return $route;
    }

    protected function getInvalidId()
    {
        return self::ID_NOT_VALID;
    }
}
