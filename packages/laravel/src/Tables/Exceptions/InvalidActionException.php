<?php

namespace Hybridly\Tables\Exceptions;

class InvalidActionException extends \Exception
{
    public function __construct(string $action, string $table)
    {
        parent::__construct(sprintf("Action [%s] does not exist in table [%s].", $action, $table));
    }
}
