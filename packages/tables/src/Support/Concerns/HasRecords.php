<?php

namespace Hybridly\Tables\Support\Concerns;

use Hybridly\Tables\Columns\BaseColumn;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/** @mixin \Hybridly\Tables\Table */
trait HasRecords
{
    private mixed $cachedRecords = null;
    protected Collection|null $records = null;
    protected int $recordsPerPage = 15;
    protected ?string $model = null;

    public function getPaginatedRecords(): Paginator
    {
        return $this->cachedRecords ??= $this->transformPaginatedRecords();
    }

    protected function transformPaginatedRecords(): Paginator
    {
        $paginatedRecords = $this->paginateRecords($this->getFilteredQuery());

        $columnNames = $this->getTableColumns()->map->getName();
        $columnsWithTransforms = $this->getTableColumns()->filter(function (BaseColumn $column) {
            return $column->canTransformValue();
        });

        if ($columnsWithTransforms->isEmpty()) {
            return $paginatedRecords;
        }

        $result = $paginatedRecords->through(fn (Model $record) => array_filter([
            ...$record->toArray(),
            ...$columnsWithTransforms->mapWithKeys(function (BaseColumn $column) use ($record) {
                return [$column->getName() => $column->getTransformedValue($record)];
            }),
        ], fn (string $key) => \in_array($key, [...$columnNames->toArray(), $this->getKeyName()], true), \ARRAY_FILTER_USE_KEY));

        return $result;
    }

    protected function paginateRecords(Builder $query): Paginator
    {
        return $query->paginate(
            pageName: $this->formatScope('page'),
            perPage: $this->recordsPerPage,
        )->withQueryString();
    }

    public function getFilteredQuery(): Builder
    {
        $query = $this->getTableQuery();

        $this->applyQueryTransforms($query);
        $this->applyFiltersToTableQuery($query);
        $this->applySortingToTableQuery($query);

        return $query;
    }

    public function getModelClass(): string
    {
        return $this->model ?? str(static::class)
            ->classBasename()
            ->beforeLast('Table')
            ->prepend('\\App\\Models\\')
            ->toString();
    }

    public function getKeyName(): string
    {
        return resolve($this->getModelClass())->getKeyName();
    }
}
