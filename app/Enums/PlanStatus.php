<?php

namespace App\Enums;

enum PlanStatus: int
{
    use EnumTrait;

    case INACTIVE = 0;
    
    case ACTIVE = 1;

    case LIMIT_EXCEEDED = 2;
}
