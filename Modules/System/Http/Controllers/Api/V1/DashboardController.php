<?php

namespace Modules\System\Http\Controllers\Api\V1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\System\Services\DashboardService;

/**
 * @group Dashboard
 * @authenticated
 *
 * Dashboard permissions
 */
class DashboardController extends Controller
{
    use ApiResponser;

    private $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    /**
     *
     *
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Request $request)
    {
        if (!$request->headers->has('user-id')) {
            return $this->errorResponse('user-id is required on header!');
        }

        $id = $request->header('user-id');

        $data = $this->service->show($id);
        return $this->successResponse($data);
    }
}
