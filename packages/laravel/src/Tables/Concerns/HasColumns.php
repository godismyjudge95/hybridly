<?php

namespace Hybridly\Tables\Concerns;

use Hybridly\Tables\Columns\BaseColumn;
use Illuminate\Support\Collection;

trait HasColumns
{
    private mixed $cachedColumns = null;

    protected function getTableColumns(): Collection
    {
        return $this->cachedColumns ??= collect($this->defineColumns())
            ->filter(fn (BaseColumn $column): bool => !$column->isHidden());
    }
}
