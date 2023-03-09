<?php

namespace Hybridly\Tables\Exceptions;

use Hybridly\Tables\Contracts\HasTable;

class InvalidTableException extends \Exception
{
    public function __construct(string $table)
    {
        parent::__construct(sprintf('Table [%s] must implement the [%s] interface.', $table, HasTable::class));
    }
}
