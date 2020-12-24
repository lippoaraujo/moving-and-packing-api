<?php

namespace Modules\Moving\Http\Controllers\Api\V1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Moving\Http\Requests\Image\ImageRequest;
use Modules\Moving\Services\ImageService;

/**
 * @group Image
 * @authenticated
 *
 * APIs for managing images
 */
class ImageController extends Controller
{
    use ApiResponser;

    private $service;

    public function __construct(ImageService $service)
    {
        $this->service = $service;
        $this->middleware('can:images');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->authorize('images', 'index');
        $data = $this->service->index();
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ImageRequest $request)
    {
        $this->authorize('images', 'store');
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
        $this->authorize('images', 'show');
        $data = $this->service->show($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ImageRequest $request, $id)
    {
        $this->authorize('images', 'update');
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
        $this->authorize('images', 'destroy');
        $data = $this->service->destroy($id);
        return $this->successResponse($data);
    }
}
