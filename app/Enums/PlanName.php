<?php

namespace App\Enums;

enum PlanName: string
{
    use EnumTrait;

    case FREE = 'FREE';

    case PRO = 'PRO';

}
