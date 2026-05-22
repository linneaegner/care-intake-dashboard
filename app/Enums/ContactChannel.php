<?php

namespace App\Enums;

enum ContactChannel: string
{
    case WebForm = 'web_form';
    case Email = 'email';
    case Phone = 'phone';
    case Referral = 'referral';

    public function label(): string
    {
        return match ($this) {
            self::WebForm => 'Webbformulär',
            self::Email => 'E-post',
            self::Phone => 'Telefon',
            self::Referral => 'Remiss',
        };
    }
}
