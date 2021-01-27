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
        $user    = auth('api')->user();
        $sellers = $user->role('Seller')->get();

        return $sellers;
    }
}
