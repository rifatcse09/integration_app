<?php

namespace App\Enums;

enum ShopStatus: int
{
    use EnumTrait;

    case ACTIVE = 1;

    case INACTIVE = 0;
}
