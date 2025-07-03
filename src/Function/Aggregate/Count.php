<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Aggregate;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class Count implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
        private readonly bool $distinct = false,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $value = $this->stringize($grammar, $this->value);

        return match ($this->distinct) {
            true  => \sprintf('count(distinct %s)', $value),
            false => \sprintf('count(%s)', $value),
        };
    }
}
