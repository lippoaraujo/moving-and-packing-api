<?php

namespace Modules\System\Http\Controllers\Api\V1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\System\Http\Requests\Action\ActionRequest;
use Modules\System\Services\ActionService;

/**
 * @group Actions
 * @authenticated
 *
 * APIs for managing Actions
 */
class ActionController extends Controller
{
    use ApiResponser;

    private $service;

    public function __construct(ActionService $service)
    {
        $this->service = $service;
        $this->middleware('can:actions');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->authorize('actions', 'index');
        $data = $this->service->index();
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ActionRequest $request)
    {
        $this->authorize('actions', 'store');
        $data = $this->service->store($request->all());
        return $this->successResponse($data, Response::HTTP_CREATED);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $this->authorize('actions', 'show');
        $data = $this->service->show($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ActionRequest $request, $id)
    {
        $this->authorize('actions', 'update');
        $data = $this->service->update($request->all(), $id);
        return $this->successResponse($data);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->authorize('actions', 'destroy');
        $data = $this->service->destroy($id);
        return $this->successResponse($data);
    }
}
