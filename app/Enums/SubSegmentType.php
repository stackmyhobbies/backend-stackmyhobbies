<?php

namespace App\Enums;

enum SubSegmentType: string
{
    case CHAPTER = 'chapter';
    case EPISODE = 'episode';
    case PAGE = 'page';
    case OVA = 'ova';
    case SPECIAL = 'special';
    case PART = 'part';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
