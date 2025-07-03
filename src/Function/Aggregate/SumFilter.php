<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Aggregate;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class SumFilter implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
        private readonly Expression $filter,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $value = $this->stringize($grammar, $this->value);
        $filter = $this->stringize($grammar, $this->filter);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlsrv' => \sprintf('sum(case when %s then %s else 0 end)', $filter, $value),
            'pgsql', 'sqlite' => \sprintf('sum(%s) filter (where %s)', $value, $filter),
        };
    }
}
