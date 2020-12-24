<?php

namespace Modules\Moving\Repositories\Eloquent;

use Modules\Moving\Entities\Image;
use App\Repositories\Core\BaseEloquentRepository;
use Modules\Moving\Repositories\Contracts\ImageRepositoryInterface;

class EloquentImageRepository extends BaseEloquentRepository implements ImageRepositoryInterface
{
    public function entity()
    {
        return Image::class;
    }
}
