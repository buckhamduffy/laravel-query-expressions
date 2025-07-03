<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Comparison;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class Between implements ConditionExpression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
        private readonly string|Expression $min,
        private readonly string|Expression $max,

    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value = $this->stringize($grammar, $this->value);
        $min = $this->stringize($grammar, $this->min);
        $max = $this->stringize($grammar, $this->max);

        return \sprintf('(%s between %s and %s)', $value, $min, $max);
    }
}
