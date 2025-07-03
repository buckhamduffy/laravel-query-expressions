<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Language;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class CaseRule implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $result,
        private readonly ConditionExpression $condition,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $condition = $this->stringize($grammar, $this->condition);
        $result = $this->stringize($grammar, $this->result);

        return "when {$condition} then {$result}";
    }
}
