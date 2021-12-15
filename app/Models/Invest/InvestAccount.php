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
}
