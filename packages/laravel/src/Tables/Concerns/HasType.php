<?php

namespace Hybridly\Tables\Concerns;

trait HasType
{
    protected ?string $type = null;

    /**
     * Unique type identifier for this component.
     */
    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
}
