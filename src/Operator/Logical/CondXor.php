<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Logical;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class CondXor implements ConditionExpression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly Expression $value1,
        private readonly Expression $value2,

    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value1 = $this->stringize($grammar, $this->value1);
        $value2 = $this->stringize($grammar, $this->value2);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => "({$value1} xor {$value2})",
            'pgsql', 'sqlite', 'sqlsrv' => "(({$value1} and not {$value2}) or (not {$value1} and {$value2}))",
        };
    }
}
