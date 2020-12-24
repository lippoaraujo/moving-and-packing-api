<?php

namespace Modules\Moving\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Moving\Entities\Seller;

class SellerTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/moving/sellers";

    /**
     * @test
     */
    public function given_noid_when_getting_seller_returns_all_sellers_data()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', self::ROUTE_URL);

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure(true));
    }

    private function getJsonStructure(bool $hasMany = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'usergroup_id',
                ]];
        } else {
            $json = [
                'id',
                'name',
                'email',
                'email_verified_at',
                'usergroup_id',
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}
