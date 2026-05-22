<?php

namespace App\Models;

use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use App\Enums\CaseType;
use App\Enums\ContactChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntakeCase extends Model
{
    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'priority' => 'normal',
        'status' => 'new',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'alias',
        'contact_channel',
        'case_type',
        'priority',
        'status',
        'internal_note',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'contact_channel' => ContactChannel::class,
            'case_type' => CaseType::class,
            'priority' => CasePriority::class,
            'status' => CaseStatus::class,
        ];
    }

    /**
     * @return HasMany<CaseEvent, $this>
     */
    public function events(): HasMany
    {
        return $this->hasMany(CaseEvent::class)->orderByDesc('created_at');
    }
}
