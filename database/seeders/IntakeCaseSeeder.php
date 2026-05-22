<?php

namespace Database\Seeders;

use App\Enums\CaseEventType;
use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use App\Enums\CaseType;
use App\Enums\ContactChannel;
use App\Models\CaseEvent;
use App\Models\IntakeCase;
use Illuminate\Database\Seeder;

class IntakeCaseSeeder extends Seeder
{
    public function run(): void
    {
        $cases = [
            [
                'alias' => 'Klient A',
                'contact_channel' => ContactChannel::WebForm,
                'case_type' => CaseType::Therapy,
                'priority' => CasePriority::High,
                'status' => CaseStatus::New,
                'internal_note' => null,
                'events' => [],
            ],
            [
                'alias' => 'Referens 1042',
                'contact_channel' => ContactChannel::Referral,
                'case_type' => CaseType::NpfAssessment,
                'priority' => CasePriority::Normal,
                'status' => CaseStatus::Contacted,
                'internal_note' => 'Vårdcentralen har skickat remiss. Inväntar journalsamtycke.',
                'events' => [
                    [
                        'type' => CaseEventType::StatusChanged,
                        'description' => 'Status ändrad från Ny till Kontaktad',
                        'metadata' => ['from' => 'new', 'to' => 'contacted'],
                    ],
                ],
            ],
            [
                'alias' => 'Företag X – avdelning HR',
                'contact_channel' => ContactChannel::Email,
                'case_type' => CaseType::OccupationalHealth,
                'priority' => CasePriority::Normal,
                'status' => CaseStatus::Booked,
                'internal_note' => 'Introduktionsmöte bokat med HR-ansvarig.',
                'events' => [
                    [
                        'type' => CaseEventType::StatusChanged,
                        'description' => 'Status ändrad från Ny till Kontaktad',
                        'metadata' => ['from' => 'new', 'to' => 'contacted'],
                    ],
                    [
                        'type' => CaseEventType::StatusChanged,
                        'description' => 'Status ändrad från Kontaktad till Bokad',
                        'metadata' => ['from' => 'contacted', 'to' => 'booked'],
                    ],
                    [
                        'type' => CaseEventType::NoteUpdated,
                        'description' => 'Intern anteckning tillagd',
                        'metadata' => null,
                    ],
                ],
            ],
            [
                'alias' => 'Klient B',
                'contact_channel' => ContactChannel::Phone,
                'case_type' => CaseType::Therapy,
                'priority' => CasePriority::Low,
                'status' => CaseStatus::Closed,
                'internal_note' => 'Avslutad – hänvisad till annan mottagning.',
                'events' => [
                    [
                        'type' => CaseEventType::StatusChanged,
                        'description' => 'Status ändrad från Ny till Kontaktad',
                        'metadata' => ['from' => 'new', 'to' => 'contacted'],
                    ],
                    [
                        'type' => CaseEventType::StatusChanged,
                        'description' => 'Status ändrad från Kontaktad till Stängd',
                        'metadata' => ['from' => 'contacted', 'to' => 'closed'],
                    ],
                ],
            ],
            [
                'alias' => 'Referens 2187',
                'contact_channel' => ContactChannel::WebForm,
                'case_type' => CaseType::NpfAssessment,
                'priority' => CasePriority::High,
                'status' => CaseStatus::New,
                'internal_note' => null,
                'events' => [],
            ],
            [
                'alias' => 'Klient C',
                'contact_channel' => ContactChannel::Email,
                'case_type' => CaseType::Therapy,
                'priority' => CasePriority::Normal,
                'status' => CaseStatus::Contacted,
                'internal_note' => 'Återkoppling skickad via säker e-postlänk.',
                'events' => [
                    [
                        'type' => CaseEventType::StatusChanged,
                        'description' => 'Status ändrad från Ny till Kontaktad',
                        'metadata' => ['from' => 'new', 'to' => 'contacted'],
                    ],
                ],
            ],
        ];

        foreach ($cases as $caseData) {
            $events = $caseData['events'];
            unset($caseData['events']);

            $intakeCase = IntakeCase::create($caseData);

            CaseEvent::create([
                'intake_case_id' => $intakeCase->id,
                'event_type' => CaseEventType::Created,
                'description' => 'Ärende registrerat',
                'metadata' => null,
                'created_at' => $intakeCase->created_at,
            ]);

            foreach ($events as $index => $event) {
                CaseEvent::create([
                    'intake_case_id' => $intakeCase->id,
                    'event_type' => $event['type'],
                    'description' => $event['description'],
                    'metadata' => $event['metadata'],
                    'created_at' => $intakeCase->created_at->copy()->addHours($index + 1),
                ]);
            }
        }
    }
}
