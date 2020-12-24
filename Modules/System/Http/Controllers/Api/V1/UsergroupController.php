<?php

namespace Modules\System\Http\Controllers\Api\V1;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\System\Http\Requests\Usergroup\UsergroupRequest;
use Modules\System\Services\UsergroupService;

/**
 * @group Usergroups
 * @authenticated
 *
 * APIs for managing Usergroups
 */
class UsergroupController extends Controller
{
    use ApiResponser;

    private $service;

    public function __construct(UsergroupService $service)
    {
        $this->service = $service;
        $this->middleware('can:usergroups');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->authorize('usergroups', 'index');
        $data = $this->service->index();
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(UsergroupRequest $request)
    {
        $this->authorize('usergroups', 'store');
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
        $this->authorize('usergroups', 'show');
        $data = $this->service->show($id);
        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UsergroupRequest $request, $id)
    {
        $this->authorize('usergroups', 'update');
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
        $this->authorize('usergroups', 'destroy');
        $data = $this->service->destroy($id);
        return $this->successResponse($data);
    }
}
