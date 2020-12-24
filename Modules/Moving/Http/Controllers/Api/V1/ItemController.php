<?php

namespace Modules\Moving\Http\Controllers\Api\V1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Exception;
use Modules\Moving\Http\Requests\Item\ItemRequest;
use Modules\Moving\Services\ItemService;

/**
 * @group Item
 * @authenticated
 *
 * APIs for managing Items
 */
class ItemController extends Controller
{
    use ApiResponser;

    private $service;

    public function __construct(ItemService $service)
    {
        $this->service = $service;
        $this->middleware('can:items');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->authorize('items', 'index');
        $data = $this->service->index();
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ItemRequest $request)
    {
        $this->authorize('items', 'store');
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
        $this->authorize('items', 'show');
        $data = $this->service->show($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ItemRequest $request, $id)
    {
        $this->authorize('items', 'update');
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
        $this->authorize('items', 'destroy');
        $data = $this->service->destroy($id);
        return $this->successResponse($data);
    }
}
