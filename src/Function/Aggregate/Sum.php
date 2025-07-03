<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Aggregate;

class Sum extends AggregateExpression
{
    protected function aggregate(): string
    {
        return 'sum';
    }
}
