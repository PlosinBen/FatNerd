<?php

namespace App\Models\Invest;

use Illuminate\Database\Eloquent\Model;

class InvestAccount extends Model
{
    protected $table = 'invest_account';

    protected $fillable = [
        'alias',
        'contract'
    ];

    public function InvestDetails()
    {
        return $this->hasMany(InvestDetail::class, 'invest_account_id');
    }

    public function InvestHistories()
    {
        return $this->hasMany(InvestHistory::class, 'invest_account_id');
    }
}
