<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Arithmetic;

class Add extends ArithmeticExpression
{
    protected function operator(): string
    {
        return '+';
    }
}
