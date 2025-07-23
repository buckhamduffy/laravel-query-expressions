<?php

namespace BuckhamDuffy\Expressions\Function\String;

use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use BuckhamDuffy\Expressions\Value\Value;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;

class ConcatWs implements Expression
{
    use StringizeExpression;
    use IdentifiesDriver;

    /**
     * @param array<int, string|Expression> $expressions
     */
    public function __construct(
        private string $separator,
        private array  $expressions,
    )
    {
    }

    public function getValue(Grammar $grammar)
    {
        return \sprintf(
            '(CONCAT_WS(%s, %s))',
            $this->stringize($grammar, new Value($this->separator)),
            $this->join($grammar, $this->expressions, ',')
        );
    }
}
