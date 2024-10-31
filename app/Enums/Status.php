<?php

namespace App\Enums;

enum Status: int
{
    use EnumTrait;

    case INACTIVE = 0;
    case ACTIVE = 1;
    case PENDING = 2;
}
