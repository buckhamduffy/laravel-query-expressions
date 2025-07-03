<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Time;

use DateInterval;
use RuntimeException;
use DateTimeInterface;
use Carbon\CarbonInterval;
use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class TimestampBin implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $expression,
        private readonly DateInterval $step,
        private readonly ?DateTimeInterface $origin = null,
    ) {
        if ($this->step->f > 0) {
            throw new RuntimeException('timestamp binning with millisecond resolution is not supported');
        }

        if ($this->origin?->getTimestamp() < 0) {
            throw new RuntimeException('timestamp binning with an origin before 1970-01-01 is not supported');
        }
    }

    public function getValue(Grammar $grammar)
    {
        $expression = $this->stringize($grammar, $this->expression);
        $step = (int) CarbonInterval::instance($this->step)->totalSeconds;
        $origin = $this->origin?->getTimestamp() ?? 0;

        // MySQL: The expression needs to be enclosed by parentheses to be used as a default value in create table statements.
        // SQLite: The expression needs to be enclosed by parentheses to be used as a default value in create table statements.
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => "(from_unixtime(floor((unix_timestamp({$expression})-{$origin})/{$step})*{$step}+{$origin}))",
            'pgsql'  => "to_timestamp(floor((extract(epoch from {$expression})-{$origin})/{$step})*{$step}+{$origin})",
            'sqlite' => "(datetime((strftime('%s',{$expression})-{$origin})/{$step}*{$step}+{$origin},'unixepoch'))",
            'sqlsrv' => "dateadd(s,(datediff(s,'1970-01-01',{$expression})-{$origin})/{$step}*{$step}+{$origin},'1970-01-01')",
        };
    }
}
