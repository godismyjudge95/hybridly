<?php

namespace Hybridly\Tables;

use Hybridly\Tables\Actions\BulkAction;
use Hybridly\Tables\Actions\InlineAction;
use Hybridly\Tables\Columns\BaseColumn;
use Hybridly\Tables\Contracts\HasTable;
use Hybridly\Tables\Filters\BaseFilter;
use Hybridly\Tables\Concerns;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Table implements HasTable
{
    use Concerns\EvaluatesClosures;
    use Concerns\HasBulkActions;
    use Concerns\HasColumns;
    use Concerns\HasFilters;
    use Concerns\HasInlineActions;
    use Concerns\HasRecords;
    use Concerns\HasScope;
    use Concerns\HasSorts;

    protected array $queryTransforms = [];

    public function __construct(
        protected Request $request,
    ) {
        $this->configure();
    }

    public static function make(): static
    {
        return resolve(static::class);
    }

    protected function configure(): void
    {
    }

    /** @var array<BaseFilter> */
    protected function defineFilters(): array
    {
        return [];
    }

    /** @var array<BaseColumn> */
    protected function defineColumns(): array
    {
        return [];
    }

    /** @var array<InlineAction> */
    protected function defineInlineActions(): array
    {
        return [];
    }

    /** @var array<BulkAction> */
    protected function defineBulkActions(): array
    {
        return [];
    }

    protected function getTableQuery(): Builder
    {
        return $this->getModel()->query();
    }

    protected function applyQueryTransforms(Builder $query): void
    {
        foreach ($this->queryTransforms as $transform) {
            $this->evaluate($transform, [
                'query' => $query,
            ]);
        }
    }

    public function transformQuery(\Closure $callback): static
    {
        $this->queryTransforms[] = $callback;

        return $this;
    }

    protected function getModel(): Model
    {
        $model = $this->getModelClass();

        return new $model();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => static::class,
            'keyName' => $this->getKeyName(),
            'records' => $this->getPaginatedRecords(),
            'columns' => $this->getTableColumns()->all(),
            'filters' => $this->getTableFilters()->all(),
            'inlineActions' => $this->getInlineActions()->all(),
            'bulkActions' => $this->getBulkActions()->all(),
            'currentSorts' => $this->getCurrentSorts(),
            'currentFilters' => $this->getCurrentFilters(),
            'scope' => $this->formatScope(),
        ];
    }
}
