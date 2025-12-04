<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\String\Format;

it('formats a numeric column')
    ->expect(new Format('amount', 2))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->decimal('amount', 8, 2);
    }, options: [
        'skip' => ['pgsql', 'sqlite', 'sqlsrv'],
    ])
    ->toBeMysql('(FORMAT(`amount`, 2))');
