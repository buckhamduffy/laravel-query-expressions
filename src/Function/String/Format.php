<?php

namespace BuckhamDuffy\Expressions\Function\String;

use RuntimeException;
use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class Format implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private string|Expression $expression,
        private int $decimals = 2,
    )
    {
    }

    public function getValue(Grammar $grammar): string
    {
        $expression = $this->stringize($grammar, $this->expression);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => \sprintf('(FORMAT(%s, %d)', $expression, $this->decimals),
            default => throw new RuntimeException('Format function is not supported for this database driver.'),
        };
    }
}
