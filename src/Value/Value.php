<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Value;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;

class Value implements Expression
{
    public function __construct(
        private readonly string|int|float|bool|null $value,
        private readonly bool $binary = false,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        return $grammar->escape($this->value, $this->binary);
    }
}
