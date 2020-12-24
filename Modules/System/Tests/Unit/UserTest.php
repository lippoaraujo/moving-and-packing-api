<?php

namespace Modules\System\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\System\Entities\User;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/system/users";

    /**
     * @test
     */
    public function given_userdata_when_posting_returns_user_stored()
    {
        $headers = $this->headers($this->getUserAdmin());
        $userData = User::factory()->passConfirmed()->make();
        $userData = $userData->toArray();

        $userData['password'] = $userData['password_confirmation'];

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $userData);

        $response->assertCreated();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_incomplete_userdata_when_posting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $userData = User::factory()->passConfirmed()->make();
        $userData = $userData->toArray();

        // $userData['password'] = $userData['password_confirmation'];

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $userData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_noid_when_getting_user_returns_all_users_data()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', self::ROUTE_URL);

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure(true));
    }

    /**
     * @test
     */
    public function given_validid_when_getting_user_returns_a_user_data()
    {
        $user = $this->getUserAdmin();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $user->getKey()));

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_user_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message',]);
    }

    /**
     * @test
     */
    public function given_userdata_withvalid_id_when_putting_returns_true()
    {
        $user = $this->getUserAdmin();

        $headers = $this->headers($this->getUserAdmin());
        $userFakeData = User::factory()->passConfirmed()->make();
        $userFakeData = $userFakeData->toArray();
        $user->email = $userFakeData['email'];

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $user->getKey()), $user->toArray());

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_userdata_withnotvalid_id_when_putting_returns_error()
    {
        $user = $this->getUserAdmin();

        $headers = $this->headers($this->getUserAdmin());
        $userFakeData = User::factory()->passConfirmed()->make();
        $userFakeData = $userFakeData->toArray();
        $user->email = $userFakeData['email'];

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $user->toArray());

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_user_id_when_deleting_returns_true()
    {
        $user = $this->getUserAdmin();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $user->getKey()));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_user_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getJsonStructure(bool $hasMany = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'usergroup_id',
                    'active'
                ]];
        } else {
            $json = [
                'id',
                'name',
                'email',
                'usergroup_id',
                'active'
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}
