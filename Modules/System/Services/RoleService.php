<?php

namespace Modules\System\Services;

use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;

class RoleService extends Controller
{
    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index()
    {
        return $this->role->orderBy('id','DESC')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     * @param array $request
     * @return Role
     */
    public function store(array $data)
    {
        $role = $this->role->create(['name' => $data['name']]);

        if(!empty($data['permission'])) {
            $role->syncPermissions($data['permission']);
        }

        return $this->repo->create($role);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Role
     */
    public function show(string $id)
    {
        return $this->role->with('permissions')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, $id)
    {
        $role = $this->role->findById($id);
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
        return $this->role->findById($id)->delete();
    }
}
