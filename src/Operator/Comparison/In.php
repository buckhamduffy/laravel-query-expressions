<?php

namespace BuckhamDuffy\Expressions\Operator\Comparison;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class In implements ConditionExpression
{
    use StringizeExpression;

    /**
     * @param array<int, Expression|string> $values
     */
    public function __construct(
        protected Expression|string $column,
        protected array $values,
    ) {
    }

    /**
     * @param array<int, Expression|string> $values
     */
    public static function make(Expression|string $column, array $values = []): self
    {
        return new static($column, $values);
    }

    public function value(Expression|string $value): self
    {
        $this->values[] = $value;

        return $this;
    }

    public function getValue(Grammar $grammar)
    {
        return \sprintf(
            '%s IN (%s)',
            $this->stringize($grammar, $this->column),
            $this->join($grammar, $this->values, ',')
        );
    }
}
