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
        return $this->repo->getAll();
    }

    /**
     * Store a newly created resource in storage.
     * @param array $request
     * @return User
     */
    public function store(array $data)
    {
        if (isset($data['password_confirmation'])) {
            unset($data['password_confirmation']);
        }

        $userAuth = auth('api')->user()->with('usergroup', 'tenant')->first();

        // if($userAuth->isDefaultUsergroup()) {
        $data['usergroup_id'] = $userAuth->usergroup->id;
        $data['tenant_id']    = $userAuth->tenant->id;
        // } else {
        //     throw new AccessDeniedHttpException('user not authorized for this action!');
        // }

        return $this->repo->create($data);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return User
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
