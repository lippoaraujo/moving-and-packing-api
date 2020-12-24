<?php

namespace Modules\System\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\System\Entities\Module;

class ModuleTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/system/modules";

    /**
     * @test
     */
    public function given_module_data_when_posting_returns_module_stored()
    {
        $headers = $this->headers($this->getUserAdmin());
        $data = $this->getData();

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $data);

        $response->assertCreated();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_incomplete_module_data_when_posting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $data = $this->getData();

        if(isset($data['name'])) {
            unset($data['name']);
        }

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_noid_when_getting_module_returns_all_modules_data()
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
    public function given_valid_id_when_getting_module_returns_a_module_data()
    {
        $module = $this->getValidModule();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $module->id));

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_module_returns_error()
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
    public function given_module_data_withvalid_id_when_putting_returns_true()
    {
        $headers = $this->headers($this->getUserAdmin());
        $module = $this->getValidModule();
        $module->name = 'name updated';
        $data = $module->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $module->id), $data);

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

     /**
     * @test
     */
    public function given_module_data_with_notvalid_id_when_putting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $module = $this->getValidModule();
        $module->name = 'name updated';
        $data = $module->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $data);

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_module_id_when_deleting_returns_true()
    {
        $module = $this->getValidModule();
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $module->id));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_module_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getData()
    {
        $module = Module::factory()->make();
        $data = $module->toArray();
        return $data;
    }

    private function getValidModule(bool $toArray = false)
    {
        $module =  Module::all()->first();

        if($toArray) {
            $module = $module->toArray();
        }

        return $module;
    }

    private function getJsonStructure(bool $hasMany = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'parent_module',
                    'order',
                    'color',
                    'image',
                    'active',
                ]];
        } else {
            $json = [
                'id',
                'name',
                'description',
                'parent_module',
                'order',
                'color',
                'image',
                'active',
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}
