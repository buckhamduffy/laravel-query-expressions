<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Comparison;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class DistinctFrom implements ConditionExpression
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

        // Sqlsrv: IS DISTINCT FROM is not available in version 2017 and 2019
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => "(not {$value1} <=> {$value2})",
            'pgsql'  => "({$value1} is distinct from {$value2})",
            'sqlite' => "({$value1} is not {$value2})",
            'sqlsrv' => "({$value1} != {$value2} or ({$value1} is not null and {$value2} is null) or ({$value1} is null and {$value2} is not null))",
        };
    }
}
