<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'invest_account_id' => $this->invest_account_id,
            'period' => $this->period,
            'deposit' => $this->deposit,
            'withdraw' => $this->withdraw,
            'profit' => $this->profit,
            'expense' => $this->expense,
            'transfer' => $this->transfer,
            'balance' => $this->balance,
            'computable' => $this->computable,
            'quota' => $this->quota,

            'invest_account_alias' => $this->whenLoaded(
                'InvestAccount', fn() => $this->InvestAccount->alias
            )
        ];
    }
}
