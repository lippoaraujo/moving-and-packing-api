<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function findWhere($column, $value);
    public function findWhereFirst($column, $value);
    public function paginate($totalPage = 10);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
