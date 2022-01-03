<?php

return [
    'domain' => env('INVEST_DOMAIN'),

    'contract' => [
        'step' => 50000,
        'fee_rate' => 0.01
    ],

    'per_weight' => 5000,

    'type' => [
        'deposit' => '入金',
        'withdraw' => '出金',
        'transfer' => '出金轉存',

        'profit' => '損益分配',
        'expense' => '費用'
    ]
];
