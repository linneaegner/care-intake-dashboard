<?php

namespace App\Enums;

enum CaseStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Booked = 'booked';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Ny',
            self::Contacted => 'Kontaktad',
            self::Booked => 'Bokad',
            self::Closed => 'Stängd',
        };
    }
}
