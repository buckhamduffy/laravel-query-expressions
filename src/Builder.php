<?php

namespace BuckhamDuffy\Expressions;

use Illuminate\Database\Grammar;
use BuckhamDuffy\Expressions\Language\Cast;
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Language\CaseGroup;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

/**
 * @phpstan-import-type CastType from Cast
 */
class Builder implements Expression
{
    use StringizeExpression;

    private Expression $expression;

    public function __construct()
    {
        $this->expression = new CaseGroup();
    }

    public static function make(): self
    {
        return new self();
    }

    /**
     * Creates a new Case Group using a callback
     *
     * @param (callable(CaseGroup): void) $cb
     */
    public function case(callable $cb): self
    {
        $this->expression = new CaseGroup();
        $cb($this->expression);

        return $this;
    }

    public function alias(string $alias): self
    {
        $this->expression = new Alias($this->expression, $alias);

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

    public function getValue(Grammar $grammar)
    {
        return $this->stringize($grammar, $this->expression);
    }
}
