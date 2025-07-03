<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\String;

use Illuminate\Database\Grammar;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Function\Conditional\ManyArgumentsExpression;

class Concat extends ManyArgumentsExpression
{
    use IdentifiesDriver;

    public function getValue(Grammar $grammar): string
    {
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlsrv' => \sprintf('(concat(%s))', $this->join($grammar, $this->expressions, ',')),
            'pgsql', 'sqlite' => \sprintf('(%s)', $this->join($grammar, $this->expressions, '||')),
        };
    }
}
