<?php

namespace App\Enums;

enum AppType: int
{
    use EnumTrait;

    case TRIGGER = 0;
    case ACTION = 1;
    case BOTH = 2;
}
