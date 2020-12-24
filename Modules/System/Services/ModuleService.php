<?php

namespace Modules\System\Services;

use Illuminate\Routing\Controller;
use Modules\System\Repositories\Contracts\ModuleRepositoryInterface;

class ModuleService extends Controller
{
    private $repo;

    public function __construct(ModuleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index()
    {
        return $this->repo->getAll();
    }

    /**
     * Store a newly created resource in storage.
     * @param array $request
     * @return Module
     */
    public function store(array $data)
    {
        return $this->repo->create($data);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Module
     */
    public function show(string $id)
    {
        return $this->repo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, $id)
    {
        return $this->repo->update($data, $id);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->repo->delete($id);
    }
}
