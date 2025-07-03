<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Time;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;

class Now implements Expression
{
    use IdentifiesDriver;

    public function getValue(Grammar $grammar)
    {
        // MySQL: The expression needs to be enclosed by parentheses to be used as a default value in create table statements.
        // PostgreSQL: The CURRENT_TIMESTAMP constant is frozen within transactions.
        // SQLite: The expression needs to be enclosed by parentheses to be used as a default value in create table statements.
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlite' => '(current_timestamp)',
            'pgsql'  => 'statement_timestamp()',
            'sqlsrv' => 'current_timestamp',
        };
    }
}
