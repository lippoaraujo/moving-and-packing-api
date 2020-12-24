<?php

namespace Modules\System\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/system/dashboard";

    /**
     * @test
     */
    public function given_valid_user_id_header_when_getting_dashboard_returns_a_dashboard_data()
    {
        $user = $this->getUserAdmin();

        $headers = $this->headers($user);
        $headers['user_id'] = $user->id;

        $response = $this->withHeaders($headers)
        ->json('GET', self::ROUTE_URL);

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_no_userid_on_header_when_getting_dashboard_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', self::ROUTE_URL);

        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => 'user-id is required on header!',
            'code' => 400
        ]);
    }

    /**
     * @test
     */
    public function given_notvalid_user_id_on_header_when_getting_dashboard_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $headers['user_id'] = $this->getInvalidId();

        $response = $this->withHeaders($headers)
        ->json('GET', self::ROUTE_URL);

        $response->assertNotFound();
        $response->assertJsonStructure([
            'message',
        ]);
    }

    private function getJsonStructure()
    {
        $json = [
            'modules' => [
                '*' => [
                'id',
                'name',
                'description',
                'parent_module',
                'image',
                'active',
                'routes_permissions' => [
                    '*' => [
                        'id',
                        'name',
                        'controllers',
                        'module_id',
                        'active',
                        'actions' => [
                            '*' => [
                                'id',
                                'name',
                                'type',
                                'active',
                                'pivot' => [
                                    'route_id',
                                    'action_id'
                                ],
                            ],
                        ],
                        ],
                    ],
                ],
            ]
        ];

        $data['data'] = $json;

        return $data;
    }
}
