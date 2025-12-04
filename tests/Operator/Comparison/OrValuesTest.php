<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Value\Number;
use BuckhamDuffy\Expressions\Operator\Comparison\Equal;
use BuckhamDuffy\Expressions\Operator\Comparison\OrValues;

it('joins conditions with OR')
    ->expect(new OrValues([
        new Equal('val', new Number(1)),
        new Equal('val', new Number(2)),
    ]))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->integer('val');
    })
    ->toBeMysql('((`val` = 1) OR (`val` = 2))')
    ->toBePgsql('(("val" = 1) OR ("val" = 2))')
    ->toBeSqlite('(("val" = 1) OR ("val" = 2))')
    ->toBeSqlsrv('(([val] = 1) OR ([val] = 2))');
