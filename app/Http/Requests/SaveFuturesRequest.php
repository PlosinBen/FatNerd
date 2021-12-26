<?php

namespace App\Http\Requests;

use App\Models\Invest\InvestFutures;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveFuturesRequest extends FormRequest
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
            'period' => [
                'required',
                'date_format:Y-m',
                'before:now',
                'after:2018-09'
            ],
            'commitment' => [
                'required',
                'numeric'
            ],
            'open_interest' => [
                'required',
                'numeric'
            ],
            'profit' => [
                'required',
                'numeric'
            ]
        ];
    }
}
