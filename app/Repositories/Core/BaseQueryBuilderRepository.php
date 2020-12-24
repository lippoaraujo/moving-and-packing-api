<?php

namespace App\Repositories\Core;

use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Exceptions\RepositoryTableNotFound;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Response;

class BaseQueryBuilderRepository implements RepositoryInterface
{
    protected $table;
    protected $db;
    protected $orderBy = [
        'column' => 'id',
        'order'  => 'DESC',
    ];

    public function __construct(DatabaseManager $db)
    {
        $this->table = $this->resolveTable();
        $this->db    = $db;
    }

    public function getAll()
    {
        return $this->db->table($this->table)
                        ->orderBy($this->orderBy['column'], $this->orderBy['order'])
                        ->get();
    }

    public function findById($id)
    {
        return $this->db->table($this->table)->find($id);
    }

    public function findWhere($column, $value)
    {
        return $this->db->table($this->table)
                        ->where($column, $value)
                        ->orderBy($this->orderBy['column'], $this->orderBy['order'])
                        ->get();
    }

    public function findWhereFirst($column, $value)
    {
        return $this->db->table($this->table)
                        ->where($column, $value)
                        ->first();
    }

    public function paginate($totalPage = 10)
    {
        return $this->db->table($this->table)
                        ->orderBy($this->orderBy['column'], $this->orderBy['order'])
                        ->paginate($totalPage);
    }

    public function create(array $data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function update(array $data, $id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->update($data);
    }

    public function orderBy($column, $order = 'DESC')
    {
        $this->orderBy = [
            'column' => $column,
            'order'  => $order,
        ];

        return $this;
    }

    public function delete($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->delete();
    }

    public function resolveTable()
    {
        if (!property_exists($this, 'table')) {
            throw new RepositoryTableNotFound('Property table not found', Response::HTTP_NOT_FOUND);
        }

        return $this->table;
    }
}
