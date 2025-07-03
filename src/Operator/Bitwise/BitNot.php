<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Bitwise;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class BitNot implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value = $this->stringize($grammar, $this->value);

        return "(~{$value})";
    }
}
