<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Conditional;

use Illuminate\Database\Grammar;

class Coalesce extends ManyArgumentsExpression
{
    public function getValue(Grammar $grammar): string
    {
        return \sprintf(
            'coalesce(%s)',
            $this->join($grammar, $this->expressions, ', '),
        );
    }
}
