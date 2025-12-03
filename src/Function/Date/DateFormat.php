<?php

namespace BuckhamDuffy\Expressions\Function\Date;

use InvalidArgumentException;
use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class DateFormat implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    /**
     * @param string $format eg. mysql: '%Y-%m-%d %H:%i:%s'
     */
    public function __construct(
        private string|Expression $expression,
        private string $format,
    )
    {
    }

    public function getValue(Grammar $grammar)
    {
        $expr = $this->stringize($grammar, $this->expression);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => \sprintf("DATE_FORMAT(%s, '%s')", $expr, $this->format),
            'sqlsrv' => \sprintf("FORMAT(%s, '%s')", $expr, $this->format),
            'pgsql'  => \sprintf("TO_CHAR(%s, '%s')", $expr, $this->format),
            'sqlite' => \sprintf("STRFTIME('%s', %s)", $this->format, $expr),
            default  => throw new InvalidArgumentException('Unsupported driver'),
        };
    }
}
