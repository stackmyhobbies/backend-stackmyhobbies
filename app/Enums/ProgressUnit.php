<?php

// app/Enums/ProgressUnit.php

namespace App\Enums;

enum ProgressUnit: string
{
    case EPISODES = 'episodes';
    case PAGES = 'pages';
    case MINUTES = 'minutes';
    case CHAPTERS = 'chapters';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
