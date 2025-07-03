<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Arithmetic;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

/**
 * @internal
 */
abstract class ArithmeticExpression implements Expression
{
    use StringizeExpression;

    /** @var Expression[]|string[] */
    private readonly array $values;

    public function __construct(
        private readonly string|Expression $value1,
        private readonly string|Expression $value2,
        string|Expression ...$values,
    )
    {
        $this->values = $values;
    }

    public function getValue(Grammar $grammar): string
    {
        return \sprintf('(%s)', $this->join(
            $grammar,
            $this->expressions(),
            \sprintf(' %s ', $this->operator()),
        ));
    }

    /**
     * @return array<int, Expression|string>
     */
    protected function expressions(): array
    {
        return array_values([$this->value1, $this->value2, ...$this->values]);
    }

    abstract protected function operator(): string;
}
