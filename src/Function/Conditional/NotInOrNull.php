<?php

namespace BuckhamDuffy\Expressions\Function\Conditional;

use Illuminate\Database\Grammar;

class NotInOrNull extends NotIn
{
    public function getValue(Grammar $grammar)
    {
        return \sprintf(
            '(%s NOT IN (%s) OR %s IS NULL)',
            $this->stringize($grammar, $this->column),
            $this->join($grammar, $this->values, ', '),
            $this->stringize($grammar, $this->column),
        );
    }
}
