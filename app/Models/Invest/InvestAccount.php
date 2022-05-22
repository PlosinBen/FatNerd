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

    public function InvestProfits()
    {
        return $this->hasMany(InvestBalance::class);
    }

    public function InvestHistories()
    {
        return $this->hasMany(InvestHistory::class);
    }
}
