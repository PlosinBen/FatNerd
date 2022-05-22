<?php

namespace App\Http\Requests;

use App\Models\Invest\InvestAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property $investAccountId
 * @property $occurredAt
 * @property $type
 * @property $amount
 * @property $note
 */
class SaveInvestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'investAccountId' => [
                'required',
                Rule::exists(InvestAccount::class, 'id'),
            ],
            'occurredAt' => [
                'required',
                'date',
                'before_or_equal:now'
            ],
            'type' => [
                'required',
                Rule::in(array_keys(config('invest.type')))
            ],
            'amount' => [
                'required',
                'numeric'
            ],
            'note' => [
                'nullable',
                'string',
            ]
        ];
    }
}
