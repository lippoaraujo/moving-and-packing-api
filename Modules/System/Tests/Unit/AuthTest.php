<?php

namespace Modules\System\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/auth";

    /**
     * @test
     */
    public function given_auth_data_when_posting_login_returns_token_authentication()
    {
        $data = $this->getAdminCredentials();

        $response = $this->postJson(self::ROUTE_URL.'/login', $data, []);

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure(true));
    }

    /**
     * @test
     */
    public function given_invalid_auth_data_when_posting_login_returns_unauthorized()
    {
        $data = $this->getAdminCredentials();
        $data['password'] = 'invalidpass';

        $response = $this->postJson(self::ROUTE_URL.'/login', $data, []);

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function given_valid_token_on_header_when_posting_logout_returns_successfully()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->postJson(self::ROUTE_URL.'/logout', [], $headers);

        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'message' => 'Successfully logged out'
            ]
        ]);
    }

    /**
     * @test
     */
    public function given_invalid_token_on_header_when_posting_logout_returns_unauthorized()
    {
        $headers = $this->headers($this->getUserAdmin());
        $headers['Authorization'] = 'invalidAuth';

        $response = $this->postJson(self::ROUTE_URL.'/logout', [], $headers);
        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function given_valid_token_on_header_when_posting_refresh_returns_new_token()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->postJson(self::ROUTE_URL.'/refresh', [], $headers);
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_invalid_token_on_header_when_posting_refresh_returns_unauthorized()
    {
        $headers = $this->headers($this->getUserAdmin());
        $headers['Authorization'] = 'invalidAuth';

        $response = $this->postJson(self::ROUTE_URL.'/refresh', [], $headers);
        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function given_valid_token_on_header_when_posting_user_returns_user_auth()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->get(self::ROUTE_URL.'/user', $headers);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'usergroup_id',
                'tenant_id',
                'active'
            ]
        ]);
    }

    /**
     * @test
     */
    public function given_invalid_token_on_header_when_posting_returns_unauthorized()
    {
        $headers = $this->headers($this->getUserAdmin());
        $headers['Authorization'] = 'invalidAuth';

        $response = $this->get(self::ROUTE_URL.'/user', $headers);
        $response->assertUnauthorized();
    }

    private function getJsonStructure(bool $dasboard = false)
    {
        $json = [
            'token',
            'token_type',
            'expires_in',
        ];

        if($dasboard) {
            array_push($json, 'dashboard');
        }

        return $json;
    }
}
