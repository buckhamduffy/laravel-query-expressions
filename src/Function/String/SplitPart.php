<?php

namespace BuckhamDuffy\Expressions\Function\String;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use BuckhamDuffy\Expressions\Exceptions\UnsupportedGrammarException;

class SplitPart implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private string|Expression $string,
        private string $delimiter = '-',
        private int $part = 1,
        private string $cast = '',
    )
    {
    }

    public function getValue(Grammar $grammar): string
    {
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => $this->mysql($grammar),
            'pgsql' => $this->pgsql($grammar),
            default => throw new UnsupportedGrammarException($this->identify($grammar), $this),
        };
    }

    private function pgsql(Grammar $grammar): string
    {
        $expr = \sprintf(
            'SPLIT_PART(%s, \'%s\', %d)',
            $this->stringize($grammar, $this->string),
            $this->delimiter,
            $this->part,
        );

        if (blank($this->cast)) {
            return $expr;
        }

        return \sprintf('%s::%s', $expr, ltrim($this->cast, ':'));
    }

    private function mysql(Grammar $grammar): string
    {
        $expr = \sprintf(
            "SUBSTRING_INDEX(SUBSTRING_INDEX(%s, '%s', %d), '%s', -1)",
            $this->stringize($grammar, $this->string),
            $this->delimiter,
            $this->part,
            $this->delimiter,
        );

        if (!blank($this->cast)) {
            return \sprintf('CAST(%s AS %s)', $expr, $this->cast);
        }

        return $expr;
    }
}
