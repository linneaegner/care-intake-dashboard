<?php

namespace App\Enums;

enum CasePriority: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Låg',
            self::Normal => 'Normal',
            self::High => 'Hög',
        };
    }
}
