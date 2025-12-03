<?php

namespace BuckhamDuffy\Expressions\Function\String;

use Illuminate\Database\Grammar;
use BuckhamDuffy\Expressions\Value\Value;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class ConcatWs implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    /**
     * @param array<int, Expression|string> $expressions
     */
    public function __construct(
        private string $separator,
        private array $expressions,
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
