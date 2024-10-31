<?php

namespace App\Enums;

enum LogTitle: string
{

    use EnumTrait;

    case MEMBER_ADDED = 'Record Inserted';
    case MEMBER_UPDATED = 'Record Updated';
    case TAG_ADDED = 'Tag Added';
    case TAG_REMOVED = 'Tag removed';
    case NOT_SET = 'Not Set';
}
