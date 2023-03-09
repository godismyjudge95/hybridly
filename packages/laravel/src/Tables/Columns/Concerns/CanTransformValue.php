<?php

namespace Hybridly\Tables\Columns\Concerns;

use Illuminate\Database\Eloquent\Model;

trait CanTransformValue
{
    protected null|\Closure $getValueUsing = null;

    public function transformValueUsing(\Closure $callback): static
    {
        $this->getValueUsing = $callback;

        return $this;
    }

    public function canTransformValue(): bool
    {
        return !\is_null($this->getValueUsing);
    }

    public function getTransformedValue(Model $record): mixed
    {
        return $this->evaluate($this->getValueUsing, [
            'record' => $record,
        ]);
    }
}
