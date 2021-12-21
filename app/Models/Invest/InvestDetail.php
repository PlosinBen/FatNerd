<?php

namespace App\Models\Invest;

use Illuminate\Database\Eloquent\Model;

class InvestDetail extends Model
{
    protected $table = 'invest_detail';

    protected $fillable = [
        'occurred_at',
        'invest_user_id',
        'type',
        'amount',
        'note'
    ];

    protected $casts = [
        'occurred_at' => 'date'
    ];
}
