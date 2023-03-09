<?php

namespace Hybridly\Tables\Exceptions;

class CouldNotResolveTableException extends \Exception
{
    public function __construct(string $table)
    {
        parent::__construct(sprintf("Table [%s] could not be resolved from the container.", $table));
    }
}
