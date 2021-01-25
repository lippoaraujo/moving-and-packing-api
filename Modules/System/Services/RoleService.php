<?php

namespace Modules\System\Services;

use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Modules\System\Repositories\Contracts\RoleRepositoryInterface;

class RoleService extends Controller
{
    private $repo;

    public function __construct(RoleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index()
    {
        return $this->repo->relationships('permissions')->getAll();
    }

    /**
     * Store a newly created resource in storage.
     * @param array $request
     * @return Role
     */
    public function store(array $data)
    {
        $role = $this->repo->create(['name' => $data['name']]);

        if (!empty($data['permission'])) {
            $role->syncPermissions($data['permission']);
        }

        return $role;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Role
     */
    public function show(string $id)
    {
        return $this->repo->relationships('permissions')->findById($id);
    }

    /**
     * Update the specified resource in storage.
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, $id)
    {
        $role       = $this->repo->findById($id);
        $role->name = $data['name'];
        $role->save();

        $role->syncPermissions($data['permission']);
        return $role;
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
