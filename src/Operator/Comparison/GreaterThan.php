<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Comparison;

use BuckhamDuffy\Expressions\Operator\OperatorExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class GreaterThan extends OperatorExpression implements ConditionExpression
{
    protected function operator(): string
    {
        return '>';
    }
}
