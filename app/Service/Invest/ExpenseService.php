<?php

namespace App\Service\Invest;

use App\Service\Invest\Contract\Friend;
use App\Service\Invest\Contract\Specially;
use App\Service\Invest\Contract\Standard;

class ExpenseService
{
    protected $contracts = [
        'standard' => Standard::class,
        'friend' => Friend::class,
        'specially' => Specially::class
    ];

    /**
     * @param string $contractName
     * @param $profit
     * @return Standard
     * @throws \Exception
     */
    public function get(string $contractName)
    {
        if($this->contracts[$contractName]) {
            return app($this->contracts[$contractName]);
        }

        throw new \Exception("Undefined Contract {$contractName}");
    }
}
