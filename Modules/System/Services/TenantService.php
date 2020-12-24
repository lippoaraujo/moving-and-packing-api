<?php

namespace Modules\System\Services;

use Exception;
use Modules\System\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\System\Entities\Route;
use Modules\System\Entities\Action;
use Modules\System\Entities\Tenant;
use Modules\System\Entities\Usergroup;
use Modules\System\Repositories\Contracts\TenantRepositoryInterface;
use Throwable;

class TenantService extends Controller
{
    private $repo;

    public function __construct(TenantRepositoryInterface $repo)
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
     * @return Tenant
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            $tenant = $this->repo->create($data);

            $defaultUsergroup = $this->createDefaultUsergroup($tenant);
            $defaultUser      = $this->createDefaultUser($tenant, $defaultUsergroup, $data['password']);

            $this->attachAllPermissions($defaultUser, $defaultUsergroup);

            DB::commit();

            $tenant->default_usergroup = $defaultUsergroup;
            $tenant->default_user      = $defaultUser;
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

    private function createDefaultUsergroup(Tenant $tenant)
    {
        $defaultUsergroupName = $this->getDefaultUsergroupName();

        $defaultUsergroup = $tenant->usergroups()->create([
            'name' => $defaultUsergroupName,
            'active' => 1
        ]);

        return $defaultUsergroup;
    }

    private function createDefaultUser(Tenant $tenant, Usergroup $usergroup, $pass)
    {
        return $usergroup->users()->create([
            'name'      => $this->getDefaultUserName(),
            'email'     => $tenant->email,
            'tenant_id' => $tenant->id,
            'password'  => $pass,
            'active'    => 1,
        ]);
    }

    private function getDefaultUsergroupName()
    {
        $usergroup = Usergroup::all()->first();

        if (!empty($usergroup)) {
            $usergroupName = $usergroup->name;
        } else {
            $usergroupName = 'master';
        }

        return $usergroupName;
    }

    private function getDefaultUserName()
    {
        $user = User::all()->first();

        if (!empty($user)) {
            $userName = $user->name;
        } else {
            $userName = 'admin';
        }

        return $userName;
    }

    private function attachAllPermissions(User $user, Usergroup $usergroup)
    {
        $routes  = Route::all();
        $actions = Action::all();

        $this->defaultUserPermissions($user, $routes, $actions);
        $this->defaultUsergroupPermissions($usergroup, $routes, $actions);
    }

    private function defaultUserPermissions(User $user, Collection $routes, Collection $actions)
    {
        foreach ($routes as $route) {
            foreach ($actions as $action) {
                $user->attachRouteAction($route->id, $action->id);
            }
        }
    }

    private function defaultUsergroupPermissions(Usergroup $usergroup, Collection $routes, Collection $actions)
    {
        foreach ($routes as $route) {
            foreach ($actions as $action) {
                $usergroup->attachRouteAction($route->id, $action->id);
            }
        }
    }
}
