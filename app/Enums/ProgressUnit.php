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

    public function label(): string
    {
        return match ($this) {
            self::EPISODES => __('Episodios'),
            self::PAGES => __('Páginas'),
            self::MINUTES => __('Minutos'),
            self::CHAPTERS => __('Capítulos')
        };
    }
}
