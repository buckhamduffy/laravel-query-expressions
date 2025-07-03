<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Conditional;

use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

/**
 * @interal
 */
abstract class ManyArgumentsExpression implements Expression
{
    use StringizeExpression;

    /**
     * @param non-empty-array<int, Expression|string> $expressions
     */
    public function __construct(
        protected readonly array $expressions,
    ) {
    }
}
