<?php

namespace Tests\Feature\Api;

use App\Enums\CaseEventType;
use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use App\Enums\CaseType;
use App\Enums\ContactChannel;
use App\Models\CaseEvent;
use App\Models\IntakeCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntakeCaseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_intake_cases(): void
    {
        $this->createCase(['alias' => 'Klient A']);

        $response = $this->getJson('/api/cases');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.alias', 'Klient A')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'alias',
                        'contact_channel',
                        'case_type',
                        'priority',
                        'status',
                        'created_at',
                    ],
                ],
            ]);
    }

    public function test_can_filter_cases_by_status_and_priority(): void
    {
        $this->createCase([
            'alias' => 'Nytt ärende',
            'status' => CaseStatus::New,
            'priority' => CasePriority::High,
        ]);

        $this->createCase([
            'alias' => 'Stängt ärende',
            'status' => CaseStatus::Closed,
            'priority' => CasePriority::Low,
        ]);

        $response = $this->getJson('/api/cases?status=new&priority=high');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.alias', 'Nytt ärende');
    }

    public function test_can_search_cases_by_alias_or_case_type(): void
    {
        $this->createCase([
            'alias' => 'Referens 1042',
            'case_type' => CaseType::Therapy,
        ]);

        $this->createCase([
            'alias' => 'Annat ärende',
            'case_type' => CaseType::NpfAssessment,
        ]);

        $this->getJson('/api/cases?search=Referens')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.alias', 'Referens 1042');

        $this->getJson('/api/cases?search=npf_assessment')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.case_type', 'npf_assessment');
    }

    public function test_can_create_intake_case(): void
    {
        $response = $this->postJson('/api/cases', [
            'alias' => 'Klient D',
            'contact_channel' => 'email',
            'case_type' => 'therapy',
            'priority' => 'normal',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.alias', 'Klient D')
            ->assertJsonPath('data.status', 'new')
            ->assertJsonPath('data.contact_channel_label', 'E-post')
            ->assertJsonCount(1, 'data.events');

        $this->assertDatabaseHas('intake_cases', [
            'alias' => 'Klient D',
            'status' => 'new',
        ]);

        $this->assertDatabaseHas('case_events', [
            'event_type' => CaseEventType::Created->value,
            'description' => 'Ärende registrerat',
        ]);
    }

    public function test_create_validates_required_fields(): void
    {
        $response = $this->postJson('/api/cases', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['alias', 'contact_channel', 'case_type']);
    }

    public function test_can_show_intake_case_with_timeline(): void
    {
        $intakeCase = $this->createCase(['alias' => 'Klient B']);

        CaseEvent::create([
            'intake_case_id' => $intakeCase->id,
            'event_type' => CaseEventType::Created,
            'description' => 'Ärende registrerat',
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/cases/'.$intakeCase->id);

        $response->assertOk()
            ->assertJsonPath('data.alias', 'Klient B')
            ->assertJsonCount(1, 'data.events')
            ->assertJsonPath('data.events.0.event_type', 'created');
    }

    public function test_can_update_status_and_record_timeline_event(): void
    {
        $intakeCase = $this->createCase([
            'alias' => 'Klient C',
            'status' => CaseStatus::New,
        ]);

        $response = $this->patchJson('/api/cases/'.$intakeCase->id.'/status', [
            'status' => 'contacted',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'contacted')
            ->assertJsonPath('data.status_label', 'Kontaktad');

        $this->assertDatabaseHas('intake_cases', [
            'id' => $intakeCase->id,
            'status' => 'contacted',
        ]);

        $this->assertDatabaseHas('case_events', [
            'intake_case_id' => $intakeCase->id,
            'event_type' => CaseEventType::StatusChanged->value,
            'description' => 'Status ändrad från Ny till Kontaktad',
        ]);
    }

    public function test_update_status_validates_status_value(): void
    {
        $intakeCase = $this->createCase();

        $this->patchJson('/api/cases/'.$intakeCase->id.'/status', [
            'status' => 'invalid-status',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_can_update_internal_note(): void
    {
        $intakeCase = $this->createCase(['internal_note' => null]);

        $response = $this->patchJson('/api/cases/'.$intakeCase->id.'/note', [
            'internal_note' => 'Kontakt via säker länk.',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.internal_note', 'Kontakt via säker länk.');

        $this->assertDatabaseHas('case_events', [
            'intake_case_id' => $intakeCase->id,
            'event_type' => CaseEventType::NoteUpdated->value,
        ]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createCase(array $attributes = []): IntakeCase
    {
        return IntakeCase::create(array_merge([
            'alias' => 'Testärende',
            'contact_channel' => ContactChannel::WebForm,
            'case_type' => CaseType::Therapy,
            'priority' => CasePriority::Normal,
            'status' => CaseStatus::New,
        ], $attributes));
    }
}
