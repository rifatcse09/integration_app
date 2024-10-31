<?php

namespace App\Enums;

enum MetaFieldKey: string
{
    use EnumTrait;
    
    case STOREFRONT_ACCESS_TOKEN = 'storefront_access_token';
}
