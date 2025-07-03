<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Value;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;

class Number implements Expression
{
    public function __construct(
        private readonly int|float $value,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        return (string) $this->value;
    }
}
