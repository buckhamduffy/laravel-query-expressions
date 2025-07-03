<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Conditional;

use Illuminate\Database\Grammar;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;

class Least extends ManyArgumentsExpression
{
    use IdentifiesDriver;

    public function getValue(Grammar $grammar): string
    {
        if ($this->identify($grammar) === 'sqlsrv') {
            $expressions = array_map(fn ($expression) => \sprintf('(%s)', $expression), $this->map($grammar, $this->expressions));
        } else {
            $expressions = $this->map($grammar, $this->expressions);
        }

        $expressionsStr = implode(', ', $expressions);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'pgsql' => \sprintf('least(%s)', $expressionsStr),
            'sqlite' => \sprintf('min(%s)', $expressionsStr),
            'sqlsrv' => \sprintf('(select min(n) from (values %s) as v(n))', $expressionsStr),
        };
    }
}
