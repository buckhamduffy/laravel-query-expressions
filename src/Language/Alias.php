<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Language;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class Alias implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $expression,
        private readonly string $name,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $expression = $this->stringize($grammar, $this->expression);
        $name = match ($this->identify($grammar)) {
            'mariadb', 'mysql' => '`'.str_replace('`', '``', $this->name).'`',
            'pgsql', 'sqlite' => '"'.str_replace('"', '""', $this->name).'"',
            'sqlsrv' => '['.str_replace(']', ']]', $this->name).']',
        };

        return \sprintf('%s as %s', $expression, $name);
    }
}
