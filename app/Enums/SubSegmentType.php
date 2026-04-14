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

    public function label(): string
    {
        return match ($this) {
            self::CHAPTER => __('Capítulo'),
            self::EPISODE => __('Episodio'),
            self::PAGE => __('Página'),
            self::OVA => __('OVA'),
            self::SPECIAL => __('Especial'),
            self::PART => __('Parte'),
        };
    }
}
