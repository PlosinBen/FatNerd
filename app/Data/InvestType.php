<?php

namespace App\Data;

use App\Contract\Enum;

/**
 * @method static all()
 *
 * @method static InvestType futures()
 */
class InvestType extends Enum
{
    protected function options(): array
    {
        return [
            'futures'
        ];
    }
}
