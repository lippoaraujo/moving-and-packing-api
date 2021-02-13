<?php

namespace Modules\Moving\Http\Controllers\Api\V1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Moving\Http\Requests\Order\OrderRequest;
use Modules\Moving\Services\OrderService;

/**
 * @group Order
 * @authenticated
 *
 * APIs for managing orders
 */
class OrderController extends Controller
{
    use ApiResponser;

    private $service;

    public function __construct(OrderService $service)
    {
        $this->middleware('permission:order-list', ['only' => ['index']]);
        $this->middleware('permission:order-create', ['only' => ['store']]);
        $this->middleware('permission:order-show', ['only' => ['show']]);
        $this->middleware('permission:order-edit', ['only' => ['update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $request->boolean('get_data') ? $getData = true : $getData = false;

        $data = $this->service->index($getData);
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(OrderRequest $request)
    {
        $data = $this->service->store($request->all());
        return $this->successResponse($data, Response::HTTP_CREATED);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function show($id, Request $request)
    {
        $request->boolean('get_data') ? $getData = true : $getData = false;

        $data = $this->service->show($id, $getData);
        return $this->successResponse($data->toArray());
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(OrderRequest $request, $id)
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
