<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Tests;

use Illuminate\Database\Query\Expression;
use Illuminate\Contracts\Database\Query\ConditionExpression as ConditionExpressionContract;

class ConditionExpression extends Expression implements ConditionExpressionContract
{
}
