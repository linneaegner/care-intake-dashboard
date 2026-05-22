<?php

namespace App\Enums;

enum CaseEventType: string
{
    case Created = 'created';
    case StatusChanged = 'status_changed';
    case NoteUpdated = 'note_updated';

    public function label(): string
    {
        return match ($this) {
            self::Created => 'Skapad',
            self::StatusChanged => 'Status ändrad',
            self::NoteUpdated => 'Anteckning uppdaterad',
        };
    }
}
