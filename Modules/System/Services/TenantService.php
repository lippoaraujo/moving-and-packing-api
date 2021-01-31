<?php

namespace Modules\System\Services;

use Exception;
use Modules\System\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\System\Entities\Tenant;
use Modules\System\Repositories\Contracts\TenantRepositoryInterface;
use Spatie\Permission\Models\Role;
use Throwable;

class TenantService extends Controller
{
    private $repo;
    private $roleService;
    private $userService;

    public function __construct(TenantRepositoryInterface $repo, RoleService $roleService, UserService $userService)
    {
        $this->repo        = $repo;
        $this->roleService = $roleService;
        $this->userService = $userService;
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
     * @return Tenant
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            $tenant = $this->repo->create($data);

            $defaultTenantRole = $this->createTenantRole($tenant, 'Master');
            $defaultTenantUser = $this->createDefaultTenantUser($tenant, $defaultTenantRole, $data['password']);

            DB::commit();

            $tenant->default_role = $defaultTenantRole;
            $tenant->default_user = $defaultTenantUser;
        } catch (Throwable $ex) {
            DB::rollback();
            throw new Exception("error on creating tenant base structure: {$ex->getMessage()}");
        }

        return $tenant;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Tenant
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

    private function createTenantRole(Tenant $tenant, string $roleName)
    {

        $defaultTenantRole = $this->roleService->store([
            'name'  => $roleName,
            'tenant_id' => $tenant->id
        ]);

        return $defaultTenantRole;
    }

    private function createDefaultTenantUser(Tenant $tenant, Role $defaultTenantRole, string $pass)
    {
        $user = $this->userService->store([
            'name'      => $tenant->name,
            'email'     => $tenant->email,
            'tenant_id' => $tenant->id,
            'password'  => $pass,
        ]);

        $user->assignRole($defaultTenantRole->name);

        return $user;
    }
}
