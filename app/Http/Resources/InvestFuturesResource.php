<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestFuturesResource extends JsonResource
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
            'period' => $this->period,
            'commitment' => $this->commitment,
            'open_interest' => $this->open_interest,
            'cover_profit' => $this->cover_profit,
            'real_commitment' => $this->real_commitment,
            'net_deposit_withdraw' => $this->net_deposit_withdraw,
            'commitment_profit' => $this->commitment_profit,
            'profit' => $this->profit,
            'total_quota' => $this->total_quota,
            'profit_per_quota' => $this->profit_per_quota,
            'updated_at' => $this->updated_at
        ];
    }
}
