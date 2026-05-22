<?php

namespace App\Models;

use App\Enums\CaseEventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseEvent extends Model
{
    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'intake_case_id',
        'event_type',
        'description',
        'metadata',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_type' => CaseEventType::class,
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<IntakeCase, $this>
     */
    public function intakeCase(): BelongsTo
    {
        return $this->belongsTo(IntakeCase::class);
    }
}
