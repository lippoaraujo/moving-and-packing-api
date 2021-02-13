<?php

namespace Modules\System\Services;

use Exception;
use Modules\System\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\System\Entities\Tenant;
use Modules\System\Repositories\Contracts\TenantRepositoryInterface;
use Modules\System\Repositories\Eloquent\EloquentPermissionRepository;
use Modules\System\Tests\Unit\TenantTest;
use Spatie\Permission\Models\Role;
use Throwable;
use Torzer\Awesome\Landlord\Facades\Landlord;

class TenantService extends Controller
{
    private $repo;
    private $roleService;
    private $userService;
    private $permissionRepo;

    public function __construct(
        TenantRepositoryInterface $repo,
        RoleService $roleService,
        UserService $userService,
        EloquentPermissionRepository $permissionRepo
    )
    {
        $this->repo           = $repo;
        $this->roleService    = $roleService;
        $this->userService    = $userService;
        $this->permissionRepo = $permissionRepo;
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

            $this->createTenantPermissions($tenant);
            $this->createDefaultSellerGroup($tenant);
            $defaultTenantUser = $this->createDefaultTenantUser($tenant, $data['password']);

            DB::commit();

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
        $return = $this->repo->update($data, $id);
        $tenant = $this->show($id);
        $this->createTenantModulePermissions($tenant);
        return $return;
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

    private function createDefaultSellerGroup(Tenant $tenant)
    {
        $role = $this->roleService->store([
            'name' => 'Seller',
            'tenant_id' => $tenant->id
        ]);

        $permissions = [
            'item-list',
            'room-list',
            'seller-list',

            'packing-list',

            'order-list',
            'order-create',
            'order-show',
            'order-edit',
            'order-delete',

            'customer-list',
            'customer-create',
            'customer-show',
            'customer-edit',
            'customer-delete',

            'moving-module'
        ];

        $role->givePermissionTo($permissions);
    }

    private function createTenantPermissions(Tenant $tenant)
    {
        Landlord::addTenant($tenant);
        Landlord::applyTenantScopesToDeferredModels();
        $this->createPermissions($tenant);
        $this->createTenantModulePermissions($tenant);
    }

    private function createPermissions(Tenant $tenant)
    {
        $allPermissions = config('acl.permissions.can');

        foreach ($allPermissions as $permission) {
            // app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            $this->permissionRepo->create([
                'name' => $permission,
                'tenant' => $tenant->id
            ]);
        }
    }

    private function createTenantModulePermissions(Tenant $tenant)
    {

        $permModules = config('acl.permissions.modules');
        $this->permissionRepo->deleteWhereIn('name', $permModules);
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $tenantLoadPlan = $tenant->load('plan.modules');
        $tenantModules  = $tenantLoadPlan->plan->modules;
        $modules        = $tenantModules->pluck('name');

        foreach ($modules as $module) {
            foreach ($permModules as $permModule) {
                if (Str::contains(Str::lower($permModule), Str::lower($module))) {
                    $this->permissionRepo->create([
                        'name' => $permModule,
                        'tenant' => $tenant->id
                    ]);
                }
            }
        }
    }

    // private function createTenantRole(Tenant $tenant, string $roleName)
    // {

    //     $defaultTenantRole = $this->roleService->store([
    //         'name'  => $roleName,
    //         'tenant_id' => $tenant->id
    //     ]);

    //     return $defaultTenantRole;
    // }

    private function createDefaultTenantUser(Tenant $tenant, string $pass)
    {
        $user = $this->userService->store([
            'name'      => $tenant->name,
            'email'     => $tenant->email,
            'tenant_id' => $tenant->id,
            'password'  => $pass,
        ]);

        return $user;
    }
}
