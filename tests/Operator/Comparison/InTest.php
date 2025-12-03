<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Operator\Comparison\In;
use BuckhamDuffy\Expressions\Value\Number;

it('checks value in list of values')
    ->expect(
        In::make('val')
            ->value(new Number(1))
            ->value(new Number(2))
            ->value(new Number(3))
    )
    ->toBeExecutable(function (Blueprint $table): void {
        $table->integer('val');
    })
    ->toBeMysql('`val` IN (1,2,3)')
    ->toBePgsql('"val" IN (1,2,3)')
    ->toBeSqlite('"val" IN (1,2,3)')
    ->toBeSqlsrv('[val] IN (1,2,3)');
