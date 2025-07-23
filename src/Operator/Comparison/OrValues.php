<?php

namespace BuckhamDuffy\Expressions\Operator\Comparison;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class OrValues implements ConditionExpression
{
    use StringizeExpression;

    /**
     * @param array<int, Expression|string> $values
     */
    public function __construct(private array $values = [])
    {
    }

    /**
     * @param array<int, Expression|string> $values
     */
    public static function make(array $values = []): self
    {
        return new self($values);
    }

    public function value(Expression|string $value): self
    {
        $this->values[] = $value;

        return $this;
    }

    public function getValue(Grammar $grammar)
    {
        return \sprintf(
            '(%s)',
            $this->join($grammar, $this->values, ' OR '),
        );
    }
}
