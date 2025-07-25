<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Bitwise;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class BitXor implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value1,
        private readonly string|Expression $value2,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value1 = $this->stringize($grammar, $this->value1);
        $value2 = $this->stringize($grammar, $this->value2);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlsrv' => "({$value1} ^ {$value2})",
            'pgsql'  => "({$value1} # {$value2})",
            'sqlite' => "(({$value1} | {$value2}) - ({$value1} & {$value2}))",
        };
    }
}
