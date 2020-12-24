<?php

namespace Modules\Moving\Repositories\Eloquent;

use App\Repositories\Core\BaseEloquentRepository;
use Modules\Moving\Repositories\Contracts\SellerRepositoryInterface;

class EloquentSellerRepository extends BaseEloquentRepository implements SellerRepositoryInterface
{
    public function entity()
    {
        return null;
    }

    public function getAllSellers()
    {
        $tenant = auth('api')->user()->tenant;

        $sellersUsegroup = $tenant->usergroups->where('name', 'seller')->first();

        if (!empty($sellersUsegroup)) {
            $sellers = $tenant->usergroups->where('name', 'seller')->first()->users;
        } else {
            $sellers = [];
        }

        return $sellers;
    }
}
