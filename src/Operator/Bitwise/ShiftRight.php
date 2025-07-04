<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Bitwise;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class ShiftRight implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
        private readonly string|Expression $times,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value = $this->stringize($grammar, $this->value);
        $times = $this->stringize($grammar, $this->times);

        // Mysql: shifting negative numbers does not work because the result is always a positive 64-bit integer
        // Sqlsrv: shifting is not available in version 2017 and 2019
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlsrv' => "floor({$value} / power(2, {$times}))",
            'pgsql', 'sqlite' => "({$value} >> {$times})",
        };
    }
}
