<?php
// app/Enums/ProgressUnit.php
namespace App\Enums;

enum ProgressUnit: string
{
    case EPISODES = 'episodios';
    case PAGES = 'paginas';
    case MINUTES = 'minutos';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}