<?php

namespace BuckhamDuffy\Expressions\Function\Date;

use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;

class DateFormat implements Expression
{
    use StringizeExpression;
    use IdentifiesDriver;

    /**
     * @param string $format eg. mysql: '%Y-%m-%d %H:%i:%s'
     */
    public function __construct(
        private string|Expression $expression,
        private string            $format,
    )
    {
    }


    public function getValue(Grammar $grammar)
    {
        $expr = $this->stringize($grammar, $this->expression);
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => sprintf("DATE_FORMAT(%s, '%s')", $expr, $this->format),
            'sqlsrv' => sprintf("FORMAT(%s, '%s')", $expr, $this->format),
            'pgsql' => sprintf("TO_CHAR(%s, '%s')", $expr, $this->format),
            'sqlite' => sprintf("STRFTIME('%s', %s)", $this->format, $expr),
            default => throw new \InvalidArgumentException('Unsupported driver'),
        };
    }
}
