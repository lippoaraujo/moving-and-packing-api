<?php

namespace Modules\Moving\Http\Controllers\Api\V1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Moving\Http\Requests\Packing\PackingRequest;
use Modules\Moving\Services\PackingService;

/**
 * @group Packing
 * @authenticated
 *
 * APIs for managing packings
 */
class PackingController extends Controller
{
    use ApiResponser;

    private $service;

    public function __construct(PackingService $service)
    {
        $this->middleware('permission:packing-list', ['only' => ['index']]);
        $this->middleware('permission:packing-create', ['only' => ['store']]);
        $this->middleware('permission:packing-show', ['only' => ['show']]);
        $this->middleware('permission:packing-edit', ['only' => ['update']]);
        $this->middleware('permission:packing-delete', ['only' => ['destroy']]);

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

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(PackingRequest $request)
    {
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
        $data = $this->service->show($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(PackingRequest $request, $id)
    {
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
        $data = $this->service->destroy($id);
        return $this->successResponse($data);
    }
}
