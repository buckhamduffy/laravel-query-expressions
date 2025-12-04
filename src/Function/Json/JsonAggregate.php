<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Json;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use BuckhamDuffy\Expressions\Exceptions\UnsupportedGrammarException;

class JsonAggregate implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private Expression|string $json,
        private string $alias = '',
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $value = $this->stringize($grammar, $this->json);
        $driver = $this->identify($grammar);

        $expression = match ($driver) {
            'mariadb', 'mysql' => \sprintf('JSON_ARRAYAGG(%s)', $value),
            'pgsql'  => \sprintf('json_agg(%s)', $value),
            'sqlite' => \sprintf('json_group_array(%s)', $value),
            default  => throw new UnsupportedGrammarException($driver, $this),
        };

        if ($this->alias === '') {
            return $expression;
        }

        return \sprintf('%s as %s', $expression, $grammar->wrap($this->alias));
    }
}
