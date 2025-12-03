<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\Date\DateFormat;
use BuckhamDuffy\Expressions\Function\Time\Now;

it('formats a column with a date pattern')
    ->expect(new DateFormat('created_at', '%Y-%m-%d'))
    ->toBeExecutable(function (Blueprint $table): void {
        $table->timestamp('created_at');
    })
    ->toBeMysql("DATE_FORMAT(`created_at`, '%Y-%m-%d')")
    ->toBePgsql("TO_CHAR(\"created_at\", '%Y-%m-%d')")
    ->toBeSqlite("STRFTIME('%Y-%m-%d', \"created_at\")")
    ->toBeSqlsrv("FORMAT([created_at], '%Y-%m-%d')");

it('formats another expression')
    ->expect(new DateFormat(new Now(), '%H:%i'))
    ->toBeExecutable()
    ->toBeMysql("DATE_FORMAT((current_timestamp), '%H:%i')")
    ->toBePgsql("TO_CHAR(statement_timestamp(), '%H:%i')")
    ->toBeSqlite("STRFTIME('%H:%i', (current_timestamp))")
    ->toBeSqlsrv("FORMAT(current_timestamp, '%H:%i')");
