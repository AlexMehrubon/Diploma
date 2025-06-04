<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pagination\LengthAwarePaginator;

class Service
{
    /**
     * @var array
     */
    public array $relations = [];

    /**
     * @var Model
     */
    private Model $model;

    /**
     * @param string $modelClass
     */
    public function __construct(
        protected string $modelClass
    )
    {
        $this->model = new $this->modelClass;
    }

    /**
     * @param string $order
     * @param int|null $perPage
     * @param int $page
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function all(string $order, ?int $perPage = null, int $page = 1, array $filters = []): LengthAwarePaginator
    {
        return $this->generateQuery($order, $filters)->paginate(perPage: $perPage, page: $page);
    }

    /**
     * @param string $order
     * @param array $filters
     * @return Collection
     */
    public function allWithoutPaginate(string $order = 'id', array $filters = []): Collection
    {
        return $this->generateQuery($order, $filters)->get();
    }

    /**
     * @param string $order
     * @param array $filters
     * @return Builder
     */
    private function generateQuery(string $order, array $filters): Builder
    {
        $query = $this->model::with(array_keys($this->relations))->orderBy($order);
        $filters = array_filter($filters, fn($v) => !empty($v));
        foreach ($filters as $column => $value) {
            if (in_array($column, array_keys($this->relations))) {
                $query->whereHas($column, function ($q) use ($column, $value) {
                    $q->whereIn("$column.id", $value['id']);
                });
            } else if ($column == 'name')
                $query->where($column, 'like', '%' . $value . '%');
            else {
                $query->where($column, $value);
            }
        }
        return $query;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        $model = $this->model->create($data);
        $intersections = array_intersect_key($this->relations, $data);
        foreach ($intersections as $relationName => $relationClass) {
            $relationMethod = $model->{$relationName}();
            $values = is_array($data[$relationName]) ? $data[$relationName] : [$data[$relationName]];
            if ($relationMethod instanceof BelongsTo) {
                $related = $relationClass::find($values[0]);
                $relationMethod->associate($related);
                $model->save();
            } elseif ($relationMethod instanceof BelongsToMany) {
                $relationMethod->attach($values);
            }
        }
        return $model;
    }
}
