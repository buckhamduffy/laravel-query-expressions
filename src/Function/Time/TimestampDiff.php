<?php

namespace BuckhamDuffy\Expressions\Function\Time;

use InvalidArgumentException;
use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

/**
 * @phpstan-type TimestampDiffUnit 'DAY'|'HOUR'|'MINUTE'|'MONTH'|'QUARTER'|'SECOND'|'WEEK'|'YEAR'
 */
class TimestampDiff implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    /**
     * @param TimestampDiffUnit $unit
     */
    public function __construct(
        private string $unit,
        private Expression|string $start,
        private Expression|string $end
    )
    {
    }

    public function getValue(Grammar $grammar): string
    {
        $start = $this->stringize($grammar, $this->start);
        $end = $this->stringize($grammar, $this->end);

        return match ($this->identify($grammar)) {
            'mysql', 'mariadb' => \sprintf('TIMESTAMPDIFF(%s, %s, %s)',
                $this->unit,
                $start,
                $end,
            ),
            'pgsql' => \sprintf(
                'EXTRACT(EPOCH FROM (%s - %s)) / %d',
                $end,
                $start,
                $this->unitToSeconds($this->unit)
            ),

            'sqlite' => \sprintf(
                'ROUND((JULIANDAY(%s) - JULIANDAY(%s)) * %f)',
                $end,
                $start,
                $this->unitToFactor($this->unit)
            ),

            'sqlsrv' => \sprintf(
                'DATEDIFF(%s, %s, %s)',
                $this->unit,
                $start,
                $end
            ),
        };
    }

    private function unitToSeconds(string $unit): int
    {
        return match (strtoupper($unit)) {
            'SECOND'  => 1,
            'MINUTE'  => 60,
            'HOUR'    => 3600,
            'DAY'     => 86400,
            'WEEK'    => 604800,
            'MONTH'   => 2629800,  // Approximate (30.44 days)
            'QUARTER' => 7889400,  // Approximate (3 * 30.44)
            'YEAR'    => 31557600, // Approximate (365.25 days)
            default   => throw new InvalidArgumentException("Unsupported unit: {$unit}"),
        };
    }

    private function unitToFactor(string $unit): float
    {
        return match (strtoupper($unit)) {
            'SECOND'  => 86400.0,
            'MINUTE'  => 1440.0,
            'HOUR'    => 24.0,
            'DAY'     => 1.0,
            'WEEK'    => 1.0 / 7.0,
            'MONTH'   => 1.0 / 30.44,
            'QUARTER' => 1.0 / (30.44 * 3),
            'YEAR'    => 1.0 / 365.25,
            default   => throw new InvalidArgumentException("Unsupported unit: {$unit}"),
        };
    }
}
