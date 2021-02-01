<?php

namespace App\Repositories\Core;

use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Exceptions\RepositoryEntityNotFound;
use Illuminate\Http\Response;

class BaseEloquentRepository implements RepositoryInterface
{
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();
    }

    public function getAll()
    {
        return $this->entity->get();
    }

    public function findById($id)
    {
        return $this->entity->findOrFail($id);
    }

    public function findWhere($column, $value)
    {
        return $this->entity->where($column, $value)->get();
    }

    public function findWhereFirst($column, $value)
    {
        return $this->entity->where($column, $value)->get()->first();
    }

    public function paginate($totalPage = 10)
    {
        return $this->entity->paginate($totalPage);
    }

    public function create(array $data)
    {
        return $this->entity->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->entity->findOrFail($id)->update($data);
    }

    public function delete($id)
    {
        return $this->entity->findOrFail($id)->delete();
    }

    public function deleteByName($name)
    {
        return $this->entity->where('name', $name)->delete();
    }

    public function deleteWhereIn(string $column, array $data)
    {
        return $this->entity->whereIn($column, $data)->delete();
    }

    public function deleteWhereLike(string $column, string $data)
    {
        return $this->entity->where($column, 'like', "{$data}%")->delete();
    }

    public function relationships(...$relationships)
    {
        $this->entity = $this->entity->with($relationships);

        return $this;
    }

    public function resolveEntity()
    {
        if (!method_exists($this, 'entity')) {
            throw new RepositoryEntityNotFound("Method entity not found", Response::HTTP_NOT_FOUND);
        }

        return app($this->entity());
    }
}
