<?php

namespace Modules\Moving\Repositories\Eloquent;

use Modules\Moving\Entities\Customer;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\Moving\Repositories\Contracts\CustomerRepositoryInterface;

class EloquentCustomerRepository extends BaseEloquentRepository implements CustomerRepositoryInterface
{
    public function entity()
    {
        return Customer::class;
    }
}
