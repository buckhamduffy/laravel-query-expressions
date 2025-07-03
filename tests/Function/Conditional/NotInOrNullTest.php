<?php

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Value\Number;
use BuckhamDuffy\Expressions\Function\Conditional\NotInOrNull;

it('can check value not in or null')
    ->expect(
        NotInOrNull::make('val')
            ->value(new Number(1))
            ->value(new Number(2))
            ->value(new Number(3))
    )
    ->toBeExecutable(function(Blueprint $table): void {
        $table->integer('val');
    })
    ->toBeMysql('(`val` NOT IN (1, 2, 3) OR `val` IS NULL)')
    ->toBePgsql('("val" NOT IN (1, 2, 3) OR "val" IS NULL)')
    ->toBeSqlite('("val" NOT IN (1, 2, 3) OR "val" IS NULL)')
    ->toBeSqlsrv('([val] NOT IN (1, 2, 3) OR [val] IS NULL)');
