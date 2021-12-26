<?php

namespace App\Contract;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

abstract class Repository
{
    protected $model;

    protected int $perPage = 10;

    protected array $withTables = [];

    public function with(string $table): self
    {
        $this->withTables[] = $table;

        return $this;
    }

    public function perPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function find($id): ?Model
    {
        $model = $this->getModelInstance();
        if (count($this->withTables)) {
            $model = $model->with($this->withTables);
        }

        return $model->find($id);
    }

    /**
     * @param array|Arrayable $columns
     * @return Collection
     */
    public function fetch($columns = []): Collection
    {
        return $this->modelSetFilter($columns)->get();
    }

    /**
     * @param array|Arrayable $columns
     * @return LengthAwarePaginator
     */
    public function fetchPagination($columns = []): LengthAwarePaginator
    {
        return $this->modelSetFilter($columns)
            ->paginate($this->perPage);
    }

    /**
     * @param array|Arrayable $columns
     * @return Builder|Model
     */
    protected function modelSetFilter($columns)
    {
        if ($columns instanceof Arrayable) {
            $columns = $columns->toArray();
        }
        $columns = collect($columns);

        $model = $this->getModelInstance();

        $orderBy = $columns->pull('orderBy');
        if ($orderBy !== null) {
            $orderBy = is_array($orderBy) ? $orderBy : explode(',', $orderBy);
            foreach ($orderBy as $orderColumn) {
                $orderColumn = explode(' ', trim($orderColumn));
                $orderColumn[1] = strtolower($orderColumn[1]) === 'desc' ? 'DESC' : 'ASC';

                $model->orderBy(parseSnakeCase($orderColumn[0]), $orderColumn[1]);
            }
        }

        $columns->map(function ($value, $scopeName) use (&$model) {
            $scopeName = parseCameCase($scopeName);
            if (method_exists($model, 'scope' . ucfirst($scopeName))) {
                call_user_func([$model, $scopeName], $value);
            }
        });

        if (count($this->withTables)) {
            $model->with($this->withTables);
        }

        return $model;
    }

    /**
     * @param $columns
     * @return Model
     */
    protected function insertModel($columns): Model
    {
        $entity = $this->setEntityColumns(
            $this->getModelInstance(),
            $columns
        );

        $entity->save();

        return $entity;
    }

    /**
     * @param int|Model $id
     * @param array|Arrayable $columns
     * @return null|Model
     */
    protected function updateModel($id, $columns): ?Model
    {
        $entity = $id instanceof Model ? $id : $this->find($id);

        if ($entity === null) {
            return null;
        }

        $this
            ->setEntityColumns($entity, $columns)
            ->save();

        return $entity;
    }

    /**
     * @param Model $entity
     * @param array|Arrayable $columns
     * @return Model
     */
    protected function setEntityColumns(Model $entity, $columns): Model
    {
        foreach ($columns as $columnName => $value) {
            $entity->__set(parseSnakeCase($columnName), $value);
        }

        return $entity;
    }

    protected function getModelInstance(): Model
    {
        return app($this->model);
    }
}
