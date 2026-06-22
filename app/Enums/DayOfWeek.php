<?php

// app/Enums/DayOfWeek.php

namespace App\Enums;

enum DayOfWeek: string
{
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::MONDAY => __('Lunes'),
            self::TUESDAY => __('Martes'),
            self::WEDNESDAY => __('Miércoles'),
            self::THURSDAY => __('Jueves'),
            self::FRIDAY => __('Viernes'),
            self::SATURDAY => __('Sábado'),
            self::SUNDAY => __('Domingo')
        };
    }
}
