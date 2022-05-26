<?php

namespace App\Models\Invest;

use Illuminate\Database\Eloquent\Model;

/**
 * @method $this investAccountId(int $investAccountId)
 * @method $this type($type)
 */
class InvestProfit extends Model
{
    protected $table = 'invest_profit';

    protected $fillable = [
        'period',
        'invest_account_id',
        'type',
        'computable',
        'quota',
        'profit'
    ];

    public function scopeInvestAccountId($query, $value)
    {
        if($value) {
            $query->where('invest_account_id', $value);
        }
    }

    public function scopeType($query, $value)
    {
        if($value) {
            $query->where('type', $value);
        }
    }
}
