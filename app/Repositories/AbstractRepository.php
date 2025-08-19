<?php

namespace App\Repositories;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class AbstractRepository
{
    /**
     * @var Model|Builder
     */
    protected $model;

    protected function query()
    {
        return $this->model->query();
    }

    /**
     * @param int $id
     */
    public function all($perPage = 15)
    {
        if ($perPage > 50) {
            $perPage = 50;
        }
        return $this->query()->paginate($perPage);
    }

    /**
     * @param int|string $id
     */
    public function findById($id)
    {
        return $this->query()->find($id);
    }

    /**
     * @param array $data
     */
    public function create(array $data)
    {
        return $this->query()->create($data);
    }

    /**
     * @param int|string $id
     * @param array $data
     */
    public function update($id, array $data)
    {
        $modelResult = $this->query()->find($id);
        if ($modelResult && $modelResult->update($data)) {
            return $modelResult;
        }

        return false;
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function destroy($id)
    {
        $modelResult = $this->query()->find($id);

        if ($modelResult && $modelResult->delete()) {
            return true;
        }

        return false;
    }
}
