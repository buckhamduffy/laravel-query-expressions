<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Conditional;

use Illuminate\Database\Grammar;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;

class Greatest extends ManyArgumentsExpression
{
    use IdentifiesDriver;

    public function getValue(Grammar $grammar): string
    {
        $expressions = $this->map($grammar, $this->expressions);

        if ($this->identify($grammar) === 'sqlsrv') {
            $expressions = array_map(fn ($expression) => \sprintf('(%s)', $expression), $expressions);
        }

        $expressionsStr = implode(', ', $expressions);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'pgsql' => \sprintf('greatest(%s)', $expressionsStr),
            'sqlite' => \sprintf('max(%s)', $expressionsStr),
            'sqlsrv' => \sprintf('(select max(n) from (values %s) as v(n))', $expressionsStr),
        };
    }
}
