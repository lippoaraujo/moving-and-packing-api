<?php

namespace Modules\Moving\Http\Controllers\Api\V1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Moving\Http\Requests\Seller\SellerRequest;
use Modules\Moving\Services\SellerService;

/**
 * @group Seller
 * @authenticated
 *
 * APIs for managing sellers
 */
class SellerController extends Controller
{
    use ApiResponser;

    private $service;

    public function __construct(SellerService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = $this->service->index();
        return $this->successResponse($data);
    }
}
