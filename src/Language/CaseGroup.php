<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Language;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class CaseGroup implements Expression
{
    use StringizeExpression;

    private ?string $alias = null;

    /**
     * @param array<int, CaseRule> $when
     */
    public function __construct(private array $when = [], private string|Expression|null $else = null)
    {
    }

    /**
     * @param array<int, CaseRule> $when
     */
    public static function make(array $when = [], string|Expression|null $else = null): self
    {
        return new self($when, $else);
    }

    public function when(ConditionExpression $condition, string|Expression $result): self
    {
        $this->when[] = new CaseRule($result, $condition);

        return $this;
    }

    public function then(string|Expression|null $else): self
    {
        $this->else = $else;

        return $this;
    }

    public function alias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getValue(Grammar $grammar): string
    {
        $conditions = collect($this->when)
            ->map(fn (CaseRule $rule) => $this->stringize($grammar, $rule))
            ->join(' ');

        $result = match ($this->else) {
            null    => \sprintf('(case %s end)', $conditions),
            default => \sprintf('(case %s else %s end)', $conditions, $this->stringize($grammar, $this->else)),
        };

        if (!$this->alias) {
            return $result;
        }

        return \sprintf('%s as %s', $result, $this->alias);
    }
}
