<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Operator\Arithmetic;

use InvalidArgumentException;
use Illuminate\Database\Grammar;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class Power extends ArithmeticExpression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function getValue(Grammar $grammar): string
    {
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlite', 'sqlsrv' => $this->buildPowerFunctionChain($grammar),
            'pgsql' => parent::getValue($grammar),
        };
    }

    protected function buildPowerFunctionChain(Grammar $grammar): string
    {
        $expressions = $this->expressions();

        if (\count($expressions) < 2) {
            throw new InvalidArgumentException('At least two values are required for the power operation.');
        }

        // Build the initial expressions by using the two required parameters of the object.
        $value = array_shift($expressions);
        $expression = (string) $this->stringize($grammar, $value);

        // For each remaining value call the power function again with the last result and the new value.
        foreach ($expressions as $value) {
            if ($value) {
                $expression = \sprintf('power(%s, %s)', $expression, $this->stringize($grammar, $value));
            }
        }

        return $expression;
    }

    protected function operator(): string
    {
        return '^';
    }
}
