<?php

namespace BuckhamDuffy\Expressions;

use Illuminate\Database\Grammar;
use BuckhamDuffy\Expressions\Language\Cast;
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Function\Math\Abs;
use BuckhamDuffy\Expressions\Language\CaseGroup;
use BuckhamDuffy\Expressions\Function\String\Wrap;
use BuckhamDuffy\Expressions\Function\String\Lower;
use BuckhamDuffy\Expressions\Function\String\Upper;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Function\Aggregate\Avg;
use BuckhamDuffy\Expressions\Function\Aggregate\Max;
use BuckhamDuffy\Expressions\Function\Aggregate\Min;
use BuckhamDuffy\Expressions\Function\Aggregate\Sum;
use BuckhamDuffy\Expressions\Function\Aggregate\Count;
use BuckhamDuffy\Expressions\Operator\Comparison\Equal;
use BuckhamDuffy\Expressions\Operator\Comparison\Between;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use BuckhamDuffy\Expressions\Function\Aggregate\CountFilter;

/**
 * @phpstan-import-type CastType from Cast
 */
class ExpressionBuilder implements Expression
{
    use StringizeExpression;

    public function __construct(protected ?Expression $expression = null)
    {
    }

    public static function make(?Expression $expression = null): self
    {
        return new self($expression);
    }

    /**
     * Creates a new Case Group using a callback. This will overwrite any existing expression.
     * @param (callable(CaseGroup): void) $cb
     */
    public function case(callable $cb): self
    {
        $this->expression = new CaseGroup();
        $cb($this->expression);

        return $this;
    }

    public function alias(string $alias, null|string|Expression $expression = null): self
    {
        $this->expression = new Alias($expression ?? $this->expression, $alias);

        return $this;
    }

    /**
     * @param CastType $type
     */
    public function cast(string $type, null|string|Expression $expression = null): self
    {
        $this->expression = new Cast($expression ?? $this->expression, $type);

        return $this;
    }

    public function avg(null|string|Expression $expression = null): self
    {
        $this->expression = new Avg($expression ?? $this->expression);

        return $this;
    }

    public function count(bool $distinct = false, null|string|Expression $expression = null): self
    {
        $this->expression = new Count($expression ?? $this->expression, $distinct);

        return $this;
    }

    public function countFilter(null|string|Expression $expression = null): self
    {
        $this->expression = new CountFilter($expression ?? $this->expression);

        return $this;
    }

    public function max(null|string|Expression $expression = null): self
    {
        $this->expression = new Max($expression ?? $this->expression);

        return $this;
    }

    public function min(null|string|Expression $expression = null): self
    {
        $this->expression = new Min($expression ?? $this->expression);

        return $this;
    }

    public function sum(null|string|Expression $expression = null): self
    {
        $this->expression = new Sum($expression ?? $this->expression);

        return $this;
    }

    public function abs(null|string|Expression $expression = null): self
    {
        $this->expression = new Abs($expression ?? $this->expression);

        return $this;
    }

    public function lower(null|string|Expression $expression = null): self
    {
        $this->expression = new Lower($expression ?? $this->expression);

        return $this;
    }

    public function upper(null|string|Expression $expression = null): self
    {
        $this->expression = new Upper($expression ?? $this->expression);

        return $this;
    }

    public function wrap(null|string|Expression $expression = null): self
    {
        $this->expression = new Wrap($expression ?? $this->expression);

        return $this;
    }

    public function between(string|Expression $min, string|Expression $max, string|Expression|null $expression)
    {
        $this->expression = new Between($expression ?? $this->expression, $min, $max);

        return $this;
    }

    public function equal(string|Expression $value2, string|Expression|null $expression)
    {
        $this->expression = new Equal($expression ?? $this->expression, $value2);

        return $this;
    }

    public function getValue(Grammar $grammar)
    {
        return $this->stringize($grammar, $this->expression);
    }
}
