<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\String;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class Lower implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $expression,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $expression = $this->stringize($grammar, $this->expression);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlite' => \sprintf('(lower(%s))', $expression),
            'pgsql', 'sqlsrv' => \sprintf('lower(%s)', $expression),
        };
    }
}
