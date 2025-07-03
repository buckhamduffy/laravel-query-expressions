<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Logical;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class CondNot implements ConditionExpression
{
    use StringizeExpression;

    public function __construct(
        private readonly Expression $value,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value = $this->stringize($grammar, $this->value);

        return "(not {$value})";
    }
}
