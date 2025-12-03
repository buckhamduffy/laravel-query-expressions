<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\Time\TimestampDiff;

it('calculates timestamp differences')
    ->expect(new TimestampDiff('MINUTE', 'started_at', 'ended_at'))
    ->toBeExecutable(function (Blueprint $table): void {
        $table->timestamp('started_at');
        $table->timestamp('ended_at');
    })
    ->toBeMysql('TIMESTAMPDIFF(MINUTE, `started_at`, `ended_at`)')
    ->toBePgsql('EXTRACT(EPOCH FROM ("ended_at" - "started_at")) / 60')
    ->toBeSqlite('ROUND((JULIANDAY("ended_at") - JULIANDAY("started_at")) * 1440.000000)')
    ->toBeSqlsrv('DATEDIFF(MINUTE, [started_at], [ended_at])');
