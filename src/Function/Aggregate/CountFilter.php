<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Aggregate;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class CountFilter implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly Expression $filter,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $filter = $this->stringize($grammar, $this->filter);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => \sprintf('sum(%s)', $filter),
            'pgsql', 'sqlite' => \sprintf('count(*) filter (where %s)', $filter),
            'sqlsrv' => \sprintf('sum(case when %s then 1 else 0 end)', $filter),
        };
    }
}
