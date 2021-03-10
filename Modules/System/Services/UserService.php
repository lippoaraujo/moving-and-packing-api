<?php

namespace Modules\System\Services;

use Illuminate\Routing\Controller;
use Illuminate\Validation\UnauthorizedException;
use Modules\System\Repositories\Contracts\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserService extends Controller
{
    private $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index()
    {
        return $this->repo->relationships('roles')->getAll();
    }

    /**
     * Store a newly created resource in storage.
     * @param array $request
     * @return User
     */
    public function store(array $data)
    {

        $user = $this->repo->create($data);

        if (!empty($data['roles'])) {
             $user->assignRole($data['roles']);
        }

        return $user;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return User
     */
    public function show(string $id)
    {
        return $this->repo->relationships('roles')->findById($id);
    }

    /**
     * Update the specified resource in storage.
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, $id)
    {
        $user = $this->show($id);

        $this->repo->update($data, $id);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return true;
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

    /**
     * Get all user permission.
     * @return array
     */
    public function permission()
    {
        return $this->repo->getUserAuthPermissions();
    }
}
