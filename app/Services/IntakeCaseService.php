<?php

namespace App\Services;

use App\Enums\CaseEventType;
use App\Enums\CaseStatus;
use App\Models\CaseEvent;
use App\Models\IntakeCase;

class IntakeCaseService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): IntakeCase
    {
        $intakeCase = IntakeCase::create($data);

        $this->recordEvent(
            $intakeCase,
            CaseEventType::Created,
            'Ärende registrerat',
        );

        return $intakeCase->load('events');
    }

    public function updateStatus(IntakeCase $intakeCase, CaseStatus $status): IntakeCase
    {
        $previousStatus = $intakeCase->status;

        if ($previousStatus === $status) {
            return $intakeCase->load('events');
        }

        $intakeCase->update(['status' => $status]);

        $this->recordEvent(
            $intakeCase,
            CaseEventType::StatusChanged,
            sprintf(
                'Status ändrad från %s till %s',
                $previousStatus->label(),
                $status->label(),
            ),
            [
                'from' => $previousStatus->value,
                'to' => $status->value,
            ],
        );

        return $intakeCase->fresh(['events']);
    }

    public function updateNote(IntakeCase $intakeCase, ?string $note): IntakeCase
    {
        $previousNote = $intakeCase->internal_note;

        if ($previousNote === $note) {
            return $intakeCase->load('events');
        }

        $intakeCase->update(['internal_note' => $note]);

        $this->recordEvent(
            $intakeCase,
            CaseEventType::NoteUpdated,
            $note === null || $note === ''
                ? 'Intern anteckning borttagen'
                : 'Intern anteckning uppdaterad',
        );

        return $intakeCase->fresh(['events']);
    }

    /**
     * @param  array<string, mixed>|null  $metadata
     */
    private function recordEvent(
        IntakeCase $intakeCase,
        CaseEventType $type,
        string $description,
        ?array $metadata = null,
    ): CaseEvent {
        return CaseEvent::create([
            'intake_case_id' => $intakeCase->id,
            'event_type' => $type,
            'description' => $description,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}
