<?php

namespace App\Modules\Client\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Client::class) ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'code' => ['required', 'alpha_dash', 'max:30', Rule::unique('clients', 'code')],
            'slug' => ['required', 'alpha_dash', 'max:100', Rule::unique('clients', 'slug')],
            'fee_per_student' => ['required', 'integer', 'min:0', 'max:10000000'],

            'pic_name' => ['required', 'string', 'max:120'],
            'pic_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],

            'bank_code' => ['required', 'string', 'max:20'],
            'account_holder' => ['required', 'string', 'max:120'],
            'account_number' => ['required', 'digits_between:6,20'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'fee_per_student' => 'biaya administrasi per santri',
            'pic_name' => 'nama penanggung jawab',
            'pic_email' => 'surel penanggung jawab',
            'bank_code' => 'bank',
            'account_holder' => 'nama pemilik rekening',
            'account_number' => 'nomor rekening',
        ];
    }
}
