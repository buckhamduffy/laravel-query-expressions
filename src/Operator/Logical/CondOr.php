<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Logical;

class CondOr extends LogicalExpression
{
    protected function operator(): string
    {
        return 'or';
    }
}
