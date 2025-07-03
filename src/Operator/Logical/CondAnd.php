<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Logical;

class CondAnd extends LogicalExpression
{
    protected function operator(): string
    {
        return 'and';
    }
}
