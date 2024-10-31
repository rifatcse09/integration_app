<?php

namespace App\Enums;

enum LogStatus: int
{

    use EnumTrait;

    case SUCCESS = 1;

    case FAILED = 0;

    case MISSING = 2;



}
