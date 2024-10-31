<?php

namespace App\Enums;

enum WebhookType: int
{
    use EnumTrait;
    case SHOPIFY = 0;
    case CUSTOM = 1;
}
