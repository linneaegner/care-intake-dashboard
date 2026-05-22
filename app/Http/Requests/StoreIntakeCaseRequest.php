<?php

namespace App\Http\Requests;

use App\Enums\CasePriority;
use App\Enums\CaseType;
use App\Enums\ContactChannel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIntakeCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'alias' => ['required', 'string', 'max:100'],
            'contact_channel' => ['required', Rule::enum(ContactChannel::class)],
            'case_type' => ['required', Rule::enum(CaseType::class)],
            'priority' => ['sometimes', Rule::enum(CasePriority::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'alias.required' => 'Ange ett alias eller referensnamn.',
            'alias.max' => 'Alias får vara högst 100 tecken.',
            'contact_channel.required' => 'Välj en kontaktkanal.',
            'contact_channel.enum' => 'Ogiltig kontaktkanal.',
            'case_type.required' => 'Välj en ärendetyp.',
            'case_type.enum' => 'Ogiltig ärendetyp.',
            'priority.enum' => 'Ogiltig prioritet.',
        ];
    }
}
