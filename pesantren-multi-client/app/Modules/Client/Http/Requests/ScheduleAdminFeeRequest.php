<?php

namespace App\Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleAdminFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('scheduleCommission', $this->route('client')) ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'fee_per_student' => ['required', 'integer', 'min:0', 'max:10000000'],
            'effective_at' => ['required', 'date', 'after:now'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'fee_per_student' => 'biaya administrasi per santri',
            'effective_at' => 'waktu berlaku',
        ];
    }
}
