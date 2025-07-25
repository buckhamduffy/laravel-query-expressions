<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Bitwise;

use BuckhamDuffy\Expressions\Operator\OperatorExpression;

class BitAnd extends OperatorExpression
{
    protected function operator(): string
    {
        return '&';
    }
}
