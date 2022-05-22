<?php

namespace App\Models\Invest;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $alias
 *
 * relation
 * @property Collection InvestBalance
 */
class InvestAccount extends Model
{
    protected $table = 'invest_account';

    protected $fillable = [
        'alias',
        'contract'
    ];

    public function InvestBalance()
    {
        return $this->hasMany(InvestBalance::class);
    }

    public function InvestHistories()
    {
        return $this->hasMany(InvestHistory::class);
    }
}
