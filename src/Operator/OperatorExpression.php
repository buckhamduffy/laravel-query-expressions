<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

/**
 * @internal
 */
abstract class OperatorExpression implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value1,
        private readonly string|Expression $value2,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        return \sprintf(
            '(%s %s %s)',
            $this->stringize($grammar, $this->value1),
            $this->operator(),
            $this->stringize($grammar, $this->value2)
        );
    }

    abstract protected function operator(): string;
}
