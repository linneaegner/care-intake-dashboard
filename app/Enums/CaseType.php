<?php

namespace App\Enums;

enum CaseType: string
{
    case Therapy = 'therapy';
    case NpfAssessment = 'npf_assessment';
    case OccupationalHealth = 'occupational_health';

    public function label(): string
    {
        return match ($this) {
            self::Therapy => 'Terapi',
            self::NpfAssessment => 'NPF-utredning',
            self::OccupationalHealth => 'Företagshälsa',
        };
    }
}
