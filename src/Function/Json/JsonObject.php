<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Json;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use BuckhamDuffy\Expressions\Exceptions\UnsupportedGrammarException;

class JsonObject implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    /** @var array<string, Expression|string> */
    private array $items = [];

    public static function make(): self
    {
        return new self();
    }

    public function item(string $key, Expression|string $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function getValue(Grammar $grammar): string
    {
        $pairs = collect($this->items)->reduce(
            function(array $carry, Expression|string $value, string $key) use ($grammar) {
                $carry[] = $grammar->escape($key);
                $carry[] = $this->stringize($grammar, $value);

                return $carry;
            },
            [],
        );

        $payload = implode(', ', $pairs);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => \sprintf('JSON_OBJECT(%s)', $payload),
            'pgsql'  => \sprintf('json_build_object(%s)', $payload),
            'sqlite' => \sprintf('json_object(%s)', $payload),
            default  => throw new UnsupportedGrammarException($this->identify($grammar), $this),
        };
    }
}
