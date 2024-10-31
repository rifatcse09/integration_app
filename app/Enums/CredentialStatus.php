<?php

namespace App\Enums;

enum CredentialStatus : int
{
    use EnumTrait;

    case INACTIVE = 0;
    case ACTIVE = 1;

}
