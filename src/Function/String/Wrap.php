<?php

namespace BuckhamDuffy\Expressions\Function\String;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class Wrap  implements Expression
{
    use StringizeExpression;

    public function __construct(
        private Expression|string|Builder $expression
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        if ($this->expression instanceof Builder) {
            return \sprintf('(%s)', $this->expression->toRawSql());
        }

        return \sprintf('(%s)', $grammar->getValue($this->expression));
    }
}
