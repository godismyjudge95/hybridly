<?php

namespace Hybridly\Tables\Concerns;

use Hybridly\Tables\Actions\InlineAction;
use Illuminate\Support\Collection;

trait HasInlineActions
{
    protected mixed $cachedInlineActions = null;

    public function getInlineActions(): Collection
    {
        return $this->cachedInlineActions ??= collect($this->defineInlineActions())
            ->filter(fn (InlineAction $action): bool => !$action->isHidden());
    }
}
