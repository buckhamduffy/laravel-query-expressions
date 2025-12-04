<?php

namespace BuckhamDuffy\Expressions\Function\Conditional;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class IfElse implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly Expression|string $condition,
        private readonly Expression|string $firstValue,
        private readonly Expression|string $secondValue
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $condition = $this->stringize($grammar, $this->condition);
        $firstValue = $this->stringizeBranch($grammar, $this->firstValue);
        $secondValue = $this->stringizeBranch($grammar, $this->secondValue);

        return match ($this->identify($grammar)) {
            'mysql', 'mariadb' => \sprintf('IF(%s, %s, %s)', $condition, $firstValue, $secondValue),
            default => \sprintf('CASE WHEN %s THEN %s ELSE %s END', $condition, $firstValue, $secondValue),
        };
    }

    /**
     * Branch values may be raw expressions or simple identifiers. We wrap bare identifiers
     * to respect the current grammar, but leave complex expressions untouched.
     */
    private function stringizeBranch(Grammar $grammar, Expression|string $value): float|int|string
    {
        if (!$grammar->isExpression($value)) {
            return $grammar->wrap($value);
        }

        $string = $grammar->getValue($value);

        if (!\is_string($string)) {
            return $string;
        }

        if ($this->isIdentifier($string)) {
            return $grammar->wrap($string);
        }

        return $string;
    }

    private function isIdentifier(string $value): bool
    {
        if (preg_match('/^[`\\[\\"](.*)[`\\]\\"]$/', $value)) {
            return false;
        }

        return (bool) preg_match('/^[a-zA-Z_][\\w$]*(\\.[a-zA-Z_][\\w$]*)*$/', $value);
    }
}
