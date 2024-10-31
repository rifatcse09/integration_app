<?php

namespace App\Enums;

enum ChargeInterval: string
{
    use EnumTrait;

    case MONTHLY = "EVERY_30_DAYS";

    case ANNUAL = "ANNUAL";
}
