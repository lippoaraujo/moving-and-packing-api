<?php

namespace Modules\Moving\Services;

use Illuminate\Routing\Controller;
use Modules\Moving\Repositories\Contracts\SellerRepositoryInterface;

class SellerService extends Controller
{
    private $repo;

    public function __construct(SellerRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index()
    {
        return $this->repo->getAllSellers();
    }
}
