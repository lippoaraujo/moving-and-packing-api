<?php

namespace Modules\System\Repositories\Contracts;

use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getUserAuthPermissions(): Collection;
}
