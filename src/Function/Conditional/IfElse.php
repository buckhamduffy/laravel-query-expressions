<?php

namespace BuckhamDuffy\Expressions\Function\Conditional;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class IfElse implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly ConditionExpression $condition,
        private readonly Expression $firstValue,
        private readonly Expression $secondValue
    )
    {
    }

    public function getValue(Grammar $grammar): string
    {
        $condition = $this->stringize($grammar, $this->condition);
        $firstValue = $this->stringize($grammar, $this->firstValue);
        $secondValue = $this->stringize($grammar, $this->secondValue);

        return match ($this->identify($grammar)) {
            'mysql', 'mariadb' => \sprintf('IF(%s, %s, %s)', $condition, $firstValue, $secondValue),
            default => \sprintf('CASE WHEN %s THEN %s ELSE %s END', $condition, $firstValue, $secondValue),
        };
    }
}
