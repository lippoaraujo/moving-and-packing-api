<?php

namespace Modules\System\Repositories\Eloquent;

use Modules\System\Entities\Action;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\System\Repositories\Contracts\ActionRepositoryInterface;

class EloquentActionRepository extends BaseEloquentRepository implements ActionRepositoryInterface
{
    public function entity()
    {
        return Action::class;
    }
}
