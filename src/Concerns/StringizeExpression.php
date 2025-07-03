<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Concerns;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;

trait StringizeExpression
{
    /**
     * Converts an expression or string into a string representation.
     */
    protected function stringize(Grammar $grammar, string|Expression $expression): float|int|string
    {
        return match ($grammar->isExpression($expression)) {
            true  => $grammar->getValue($expression),
            false => $grammar->wrap($expression),
        };
    }

    /**
     * @param  array<int, Expression|string> $values
     * @return array<int, float|int|string>
     */
    protected function map(Grammar $grammar, array $values): array
    {
        return array_map(fn ($value) => $this->stringize($grammar, $value), $values);
    }

    /**
     * Converts an array of expressions into a string, using the provided glue.
     * @param array<int, Expression|string> $values
     */
    protected function join(Grammar $grammar, array $values, string $glue): string
    {
        return implode($glue, $this->map($grammar, $values));
    }
}
