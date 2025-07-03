<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Aggregate;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

/**
 * @internal
 */
abstract class AggregateExpression implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $value = $this->stringize($grammar, $this->value);
        $aggregate = $this->aggregate();

        return \sprintf('%s(%s)', $aggregate, $value);
    }

    abstract protected function aggregate(): string;
}
