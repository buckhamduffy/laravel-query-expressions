<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Aggregate;

class Avg extends AggregateExpression
{
    protected function aggregate(): string
    {
        return 'avg';
    }
}
