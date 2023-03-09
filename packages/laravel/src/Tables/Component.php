<?php

namespace Hybridly\Tables;

use Hybridly\Tables\Concerns\EvaluatesClosures;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;

abstract class Component implements \JsonSerializable
{
    use Concerns\Configurable;
    use Conditionable;
    use EvaluatesClosures;
    use Macroable;
    use Tappable;
}
